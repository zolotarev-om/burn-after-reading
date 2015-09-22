<?php

namespace App\Http\Controllers;

use app\Repositories\Repository;
use Faker\Factory;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @var Repository
     */
    private $repo;

    /**
     * Controller constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * @param $url
     */
    public function get($url)
    {
        $this->repo->getLetter($url);
    }

    /**
     * from post request
     */
    public function create()
    {
        $inputText = 'i am a private letter';
        $cryptedText = app('encrypter')->encrypt($inputText);

        $letterId = $this->repo->createCryptedLetterAndReturnId($cryptedText);
        $urls = $this->generateUniqueUrl();
        $this->repo->createLinks($urls['admin'], $urls['user'], $letterId);
    }

    /**
     * @return array
     */
    private function generateUniqueUrl()
    {
        do {
            $urlAdmin = Factory::create()->lexify('????????????????????????????????????????');
            $isUniqLinkAdm = $this->repo->verifyUniqueUrl($urlAdmin);
        } while ($isUniqLinkAdm);

        do {
            $urlUser = Factory::create()->lexify('????????????????????');
            $isUniqLinkUser = $this->repo->verifyUniqueUrl($urlUser);
        } while ($isUniqLinkUser);

        return ['admin' => $urlAdmin, 'user' => $urlUser];
    }
}
