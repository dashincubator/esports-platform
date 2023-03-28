<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Origin
 *  - https://www.npmjs.com/package/call-of-duty-api
 *  - https://documenter.getpostman.com/view/7896975/SW7aXSo5?version=latest
 *
 */

namespace App\Services\Api\ModernWarfare;

use App\DataSource\Game\Api\Match\Entity;
use Contracts\Cache\Cache;
use Contracts\MultiCurl\MultiCurl;
use Exception;

class Api
{

    private const URL = 'https://my.callofduty.com/api/papi-client/crm/cod/v2/title/%s/platform/%s/gamer/%s/matches/wz/start/%u/end/%u/details?limit=5';


    private $auth;

    private $key = 'Api:ModernWarfare:Skip';

    private $skip = [];

    private $multicurl;


    public function __construct(Auth $auth, Cache $cache, MultiCurl $multicurl)
    {
        $this->auth = $auth;
        $this->cache = $cache;
        $this->multicurl = $multicurl;
        $this->skip = $this->cache->get($this->key, function() {
            return [];
        });
    }


    public function activision(Entity ...$match) : array
    {
        return $this->fetch('uno', ...$match);
    }


    public function battle(Entity ...$match) : array
    {
        return $this->fetch('battle', ...$match);
    }


    private function fetch(string $platform, Entity ...$matches) : array
    {
        $matches = array_filter($matches, function ($match) use ($platform) {
            return !$match->isEmpty() && !in_array("{$platform}:{$match->getUsername()}", $this->skip);
        });
        $token = $this->auth->getToken();

        foreach ($matches as $match) {
            $end = time() * 1000;
            $start = abs(($match->getStartedAt() - 1) * 1000);

            if ($start <= 1000) {
                $end = 0;
                $start = 0;
            }

            $this->multicurl->set(
                $match->getUsername(),
                sprintf(self::URL, 'mw', $platform, rawurlencode($match->getUsername()), $start, $end),
                [
                    CURLOPT_HTTPHEADER => $token->getHeaders()
                ]
            );
        }

        // Mimic RPS Delay Used In Github Repo
        sleep(2);

        $responses = $this->multicurl->execute();
        $stats = [];

        foreach ($responses as $username => $response) {
            $content = json_decode($response->getContent(), true);
            $data = $content['data'] ?? [];

            $message = strtolower($data['message'] ?? '');
            $status = $content['status'] ?? '';

            if ($response->getStatusCode() !== 200 || $status !== 'success' || !$data) {
                if (strpos($message, 'not permitted') !== false) {
                    if (
                        // Invalid Username
                        strpos($message, 'user not found') !== false
                        // User Needs To Make Stats Public
                        || strpos($message, 'not allowed') !== false
                        // User Never Played Warzone Or Server API Error
                        //|| strpos($message, 'error from datastore') !== false
                    ) {
                        $this->skip[] = "{$platform}:{$username}";
                    }
                    else {
                        $this->auth->restricted($token->getEmail());
                        // Rate Limit Message Is
                        // - rate limit exceeded
                        throw new Exception(json_encode(array_merge(
                            [
                                'email' => $token->getEmail()
                            ],
                            compact('platform', 'username', 'content')
                        )));
                    }
                }
                // IP Restriction
                elseif ($response->getStatusCode() === 403) {
                    throw new Exception('Roughly 15min Temporary Modern Warfare API IP Restriction');
                }

                continue;
            }

            $found = [];

            foreach (($data['matches'] ?? []) as $match) {
                unset($match['player'], $match['rankedTeams']);

                $found[] = [
                    'data' => $match,
                    'endedAt' => $match['utcEndSeconds'],
                    'startedAt' => $match['utcStartSeconds']
                ];
            }

            $stats[$username] = $found;
        }

        $this->auth->updateCache();
        $this->cache->set($this->key, $this->skip);

        return $stats;
    }


    public function getInvalidAccountUsernames() : array
    {
        $skip = $this->skip;

        $this->cache->delete($this->key);
        $this->skip = [];

        return array_map(function($skip) {
            return array_reverse(explode(':', $skip))[0];
        }, $skip);
    }


    public function playstation(Entity ...$match) : array
    {
        return $this->fetch('psn', ...$match);
    }


    public function steam(Entity ...$match) : array
    {
        return $this->fetch('steam', ...$match);
    }


    public function xbox(Entity ...$match) : array
    {
        return $this->fetch('xbl', ...$match);
    }
}
