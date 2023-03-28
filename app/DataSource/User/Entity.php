<?php

namespace App\DataSource\User;

use App\DataSource\AbstractEntity;
use Contracts\Hashing\Hasher;
use Contracts\UUID\RandomGenerator;
use Contracts\Slug\Generator;
use Exception;

class Entity extends AbstractEntity
{

    private $cachebustAdmin = false;

    private $hasher;

    private $slug;

    private $timezones;

    private $uuid;


    protected $fillable = [
        'adminPosition', 'avatar', 'banner', 'bio', 'email', 'organization',
        'password', 'timezone', 'username', 'wagers'
    ];

    protected $hidden = [
        'membershipExpiresAt', 'password', 'signinToken'
    ];


    public function __construct(
        Generator $slug,
        Hasher $hasher,
        RandomGenerator $uuid,
        Record $record,
        array $timezones
    ) {
        parent::__construct($record);

        $this->hasher = $hasher;
        $this->slug = $slug;
        $this->timezones = $timezones;
        $this->uuid = $uuid;
    }


    public function cachebustAdmin() : bool
    {
        return $this->cachebustAdmin;
    }


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function isAdmin() : bool
    {
        return $this->get('adminPosition') > 0;
    }


    public function isGuest() : bool
    {
        return $this->isEmpty();
    }


    public function isLocked() : bool
    {
        return $this->get('locked');
    }


    public function isMembershipActive() : bool
    {
        return $this->get('membershipExpiresAt') > time();
    }


    public function isValidPassword(string $password) : bool
    {
        return $this->hasher->verify($password, $this->get('password'));
    }


    public function isValidSignin(string $hash) : bool
    {
        return $this->hasher->verify($this->get('signinToken'), $hash);
    }


    public function lock() : void
    {
        $this->set('locked', true);
        $this->set('lockedAt', time());
    }


    protected function setAdminPosition(int $position) : int
    {
        if ($this->get('adminPosition') !== $position) {
            $this->cachebustAdmin = true;
        }

        return $position;
    }


    protected function setPassword(string $password) : string
    {
        return $this->hasher->hash($password);
    }


    protected function setSlug(string $slugify) : string
    {
        return $this->slug->generate($slugify);
    }


    protected function setTimezone(string $timezone) : string
    {
        if (!in_array($timezone, $this->timezones)) {
            $timezone = $this->timezones[0];
        }

        return $timezone;
    }


    protected function setUsername(string $username) : string
    {
        $this->set('slug', $username);

        return $username;
    }


    public function signin() : string
    {
        $this->set('signinToken', $this->uuid->generate());

        return $this->hasher->hash($this->get('signinToken'));
    }


    public function toArray() : array
    {
        $data = parent::toArray();
        $data['isMembershipActive'] = $this->isMembershipActive();

        return $data;
    }


    public function unlock() : void
    {
        $this->set('locked', false);
        $this->set('lockedAt', 0);
    }


    public function updateMembershipTime(int $days) : void
    {
        $current = $this->get('membershipExpiresAt');

        if ($current < 0) {
            throw new Exception("User {$this->get('id')} Has A Negative 'membershipExpiresAt' Value");
        }
        elseif ($current > 0) {
            $current -= time();

            if ($current < 0) {
                $current = 0;
            }
        }

        $current += $days * strtotime('1 day', 0);

        // This Method Can Be Used To Decrement Membership Time As Well
        if ($current < 0) {
            $current = 0;
        }

        $this->set('membershipExpiresAt', time() + $current);
    }
}
