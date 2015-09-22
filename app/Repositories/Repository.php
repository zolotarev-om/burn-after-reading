<?php

namespace app\Repositories;

class Repository
{
    public function verifyUniqueUrl($url)
    {
        return app('db')->select('SELECT * FROM link WHERE link.url = ?', [$url]);
    }

    public function createCryptedLetterAndReturnId($cryptedText)
    {
        app('db')->insert(
            'INSERT INTO letter (text,created_at,updated_at) VALUES (?,?,?)',
            [$cryptedText, time(), time()]
        );

        $resLetId = app('db')->select('select last_insert_rowid()');
        return get_object_vars($resLetId[0])['last_insert_rowid()'];
    }

    public function createLinks($urlAdmin, $urlUser, $letterId)
    {
        try {
            app('db')->insert(
                'INSERT INTO link (url,letter_id,admin,visited,created_at,updated_at) VALUES(?,?,?,?,?,?)',
                [$urlAdmin, $letterId, true, false, time(), time()]
            );
            app('db')->insert(
                'INSERT INTO link (url,letter_id,admin,visited,created_at,updated_at) VALUES (?,?,?,?,?,?)',
                [$urlUser, $letterId, false, false, time(), time()]
            );
        } catch (\Exception $e) {
            echo $e;
        }
    }

    public function getLetter($url)
    {
        $res = app('db')->select(
            'SELECT * FROM link INNER JOIN letter ON link.letter_id = letter.id WHERE link.url = ?',
            [$url]
        );
        return $res[0];
    }
}