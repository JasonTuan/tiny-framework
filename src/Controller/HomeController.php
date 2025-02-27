<?php

namespace TinyFramework\Controller;

use Carbon\Carbon;
use TinyFramework\Models\Http\HtmlResponse;

class HomeController
{
    public function index(): HtmlResponse
    {
//        $sql = "SELECT * FROM tbl_demo";
//        $records = db()->querySelect($sql);

//        db()->beginTransaction();
//        try {
//            $sql = "INSERT INTO  ... VALUES (...);";
//            $recordInsertedId = db()->queryInsert($sql);
//
//            db()->commit();
//            return json_response(['success' => true]);
//        } catch (mysqli_sql_exception $exception) {
//            db()->rollback();
//            return json_response(['error' => true, 'message' => $exception->getMessage()]);
//        }

        return render('Home/index.tpl', [
            'name' => 'Demo',
        ]);
    }
}
