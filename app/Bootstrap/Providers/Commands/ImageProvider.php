<?php

namespace App\Bootstrap\Providers\Commands;

use Contracts\Upload\{Image, Png};
use App\Commands\Game\Upload\Banner\Command as UploadGameBannerCommand;
use App\Commands\Game\Upload\Card\Command as UploadGameCardCommand;
use App\Commands\Ladder\Upload\Banner\Command as UploadLadderBannerCommand;
use App\Commands\Ladder\Upload\Card\Command as UploadLadderCardCommand;
use App\Commands\Ladder\Team\Upload\Avatar\Command as UploadLadderTeamAvatarCommand;
use App\Commands\Ladder\Team\Upload\Banner\Command as UploadLadderTeamBannerCommand;
use App\Commands\User\Upload\Avatar\Command as UploadUserAvatarCommand;
use App\Commands\User\Upload\Banner\Command as UploadUserBannerCommand;
use App\Bootstrap\Providers\AbstractProvider;

class ImageProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerGameBannerBinding();
        $this->registerGameCardBinding();
        $this->registerLadderBannerBinding();
        $this->registerLadderCardBinding();
        $this->registerLadderTeamAvatarBinding();
        $this->registerLadderTeamBannerBinding();
        $this->registerUserAvatarBinding();
        $this->registerUserBannerBinding();
    }


    private function registerGameBannerBinding() : void
    {
        $this->container->when(UploadGameBannerCommand::class)
            ->needs(Image::class)
            ->give(function() {
                return $this->container->resolve(Image::class, [
                    $this->config->get('paths.uploads.game.banner')
                ]);
            });
    }


    private function registerGameCardBinding() : void
    {
        $this->container->when(UploadGameCardCommand::class)
            ->needs(Image::class)
            ->give(function() {
                return $this->container->resolve(Image::class, [
                    $this->config->get('paths.uploads.game.card')
                ]);
            });
    }


    private function registerLadderBannerBinding() : void
    {
        $this->container->when(UploadLadderBannerCommand::class)
            ->needs(Image::class)
            ->give(function() {
                return $this->container->resolve(Image::class, [
                    $this->config->get('paths.uploads.ladder.banner')
                ]);
            });
    }


    private function registerLadderCardBinding() : void
    {
        $this->container->when(UploadLadderCardCommand::class)
            ->needs(Image::class)
            ->give(function() {
                return $this->container->resolve(Image::class, [
                    $this->config->get('paths.uploads.ladder.card')
                ]);
            });
    }


    private function registerLadderTeamAvatarBinding() : void
    {
        $this->container->when(UploadLadderTeamAvatarCommand::class)
            ->needs(Image::class)
            ->give(function() {
                return $this->container->resolve(Image::class, [
                    $this->config->get('paths.uploads.ladder.team.avatar')
                ]);
            });
    }


    private function registerLadderTeamBannerBinding() : void
    {
        $this->container->when(UploadLadderTeamBannerCommand::class)
            ->needs(Image::class)
            ->give(function() {
                return $this->container->resolve(Image::class, [
                    $this->config->get('paths.uploads.ladder.team.banner')
                ]);
            });
    }


    // Use If Statement To Check If Organization Is Uploading
    private function registerUserAvatarBinding() : void
    {
        $this->container->when(UploadUserAvatarCommand::class)
            ->needs(Image::class)
            ->give(function() {
                return $this->container->resolve(Png::class, [
                    $this->config->get('paths.uploads.user.avatar')
                ]);
            });
    }


    private function registerUserBannerBinding() : void
    {
        $this->container->when(UploadUserBannerCommand::class)
            ->needs(Image::class)
            ->give(function() {
                return $this->container->resolve(Image::class, [
                    $this->config->get('paths.uploads.user.banner')
                ]);
            });
    }
}
