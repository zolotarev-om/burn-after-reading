<?php

namespace App\Http\Controllers;

use App\Repositories\Repository;
use DateTime;
use DateTimeZone;
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
     *
     * @return \Illuminate\View\View
     */
    public function get($url)
    {
        $letter = $this->repo->getLetter($url);
        $encryptedText = $letter->text;
        $decryptedText = $this->decrypt($encryptedText);

        if ($letter->visited == true) {
            return view('index')->with('text', 'This page already visited. And now this is unavailable');
        }
        if ($letter->admin == true) {
            $urls = $this->getExistingUrl($letter->id);
            return view('index')->with('text', $decryptedText)->with('admin', true)->with('urls', $urls);
        }
        if ($letter->visited == false && $letter->admin == false) {
            $this->repo->burnUrl($letter->url);
            return view('index')->with('text', $decryptedText);
        }
        return null;
    }

    /**
     * @return $this
     */
    public function create()
    {
        $validator = app('validator')->make(app('request')->all(), [
            'letter' => 'required|min:2|max:12555',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return view('index')->with('errors', $errors);
        } else {
            $inputText = app('request')->input('letter');
            $encryptedText = $this->encrypt($inputText);

            $letterId = $this->repo->saveCryptedLetterAndReturnId($encryptedText);
            $urls = $this->generateUniqueUrl();
            $this->repo->saveLinks($urls['admin'], $urls['user'], $letterId);

            return view('index')->with('url', $urls);
        }
    }

    /**
     * @return $this
     */
    public function createNewUserUrl()
    {
        $request = app('request');
        if ($request->ajax()) {
            $letterId = $this->repo->getLetterIdWhereUrl($request->url);
            $newUrl = $this->generateUniqueUrl(true);
            $this->repo->saveLinks(null, $newUrl['user'], $letterId);
            return view('form-user')->with('url', $newUrl)->render();
        }
        return null;
    }

    /**
     * @param $letter
     *
     * @return string
     */
    private function encrypt($letter)
    {
        $letter = htmlspecialchars(strip_tags($letter, '<b><i><sup><sub><em><strong><u><br>'));
        $encryptedText = app('encrypter')->encrypt($letter);
        return $encryptedText;
    }

    /**
     * @param bool $onlyUser
     *
     * @return array
     */
    private function generateUniqueUrl($onlyUser = false)
    {
        $res = [];

        if (!$onlyUser) {
            do {
                $urlAdmin = Factory::create()->lexify('????????????????????????????????????????');
                $isUniqLinkAdm = $this->repo->verifyUniqueUrl($urlAdmin);
            } while ($isUniqLinkAdm);
            $res['admin'] = $urlAdmin;
        }

        do {
            $urlUser = Factory::create()->lexify('????????????????????');
            $isUniqLinkUser = $this->repo->verifyUniqueUrl($urlUser);
        } while ($isUniqLinkUser);
        $res['user'] = $urlUser;

        return $res;
    }

    /**
     * @param $encryptedText
     *
     * @return string
     */
    private function decrypt($encryptedText)
    {
        $decryptedText = app('encrypter')->decrypt($encryptedText);
        $decryptedText = htmlspecialchars_decode($decryptedText);
        return $decryptedText;
    }

    /**
     * @param $letterId
     *
     * @return array
     */
    private function getExistingUrl($letterId)
    {
        $letters = $this->repo->getAllLinksWhereLetterId($letterId);
        $res = [];
        foreach ($letters as $val) {
            if ($val->visited) {
                $updated = new DateTime('now', new DateTimeZone('Europe/Moscow'));
                $updated = $updated->setTimestamp($val->updated_at);
                $updated = $updated->format('H:i d.m.Y');
            } else {
                $updated = 0;
            }

            $res[] = ['url' => $val->url, 'visited' => $val->visited, 'at' => $updated];
        }
        return $res;
    }
}
