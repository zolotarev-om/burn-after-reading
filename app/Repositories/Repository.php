<?php

namespace App\Repositories;

class Repository
{
    /**
     * @param $url
     *
     * @return array
     */
    public function verifyUniqueUrl($url)
    {
        return app('db')->select('SELECT * FROM link WHERE link.url = ?', [$url]);
    }

    /**
     * @param $cryptedText
     *
     * @return int
     */
    public function saveCryptedLetterAndReturnId($cryptedText)
    {
        app('db')->insert(
            'INSERT INTO letter (text,created_at,updated_at) VALUES (?,?,?)',
            [$cryptedText, time(), time()]
        );

        $resLetId = app('db')->select('select last_insert_rowid()');
        return get_object_vars($resLetId[0])['last_insert_rowid()'];
    }

    /**
     * @param $urlAdmin
     * @param $urlUser
     * @param $letterId
     */
    public function saveLinks($urlAdmin, $urlUser, $letterId)
    {
        try {
            if ($urlAdmin != null) {
                app('db')->insert(
                    'INSERT INTO link (url,letter_id,admin,visited,created_at,updated_at) VALUES(?,?,?,?,?,?)',
                    [$urlAdmin, $letterId, true, false, time(), time()]
                );
            }
            app('db')->insert(
                'INSERT INTO link (url,letter_id,admin,visited,created_at,updated_at) VALUES (?,?,?,?,?,?)',
                [$urlUser, $letterId, false, false, time(), time()]
            );
        } catch (\Exception $e) {
            echo $e;
        }
    }

    /**
     * @param $url
     *
     * @return object
     */
    public function getLetter($url)
    {
        $res = app('db')->select(
            'SELECT * FROM link INNER JOIN letter ON link.letter_id = letter.id WHERE link.url = ?',
            [$url]
        );
        return $res[0];
    }

    public function burnUrl($url)
    {
        try {
            app('db')->update("UPDATE link SET visited=?,updated_at=? WHERE url=?", [true, time(), $url]);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}