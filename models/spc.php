<?php 

class spc
{
    public static function load($id="all")
    {
        if($id=="all") $sql = 'SELECT * FROM `specialties`';
        else $sql = 'SELECT * FROM `specialties` WHERE id = :id';

            $db = db::getConnection();
            $result = $db->prepare($sql);// Используется подготовленный запрос

            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->setFetchMode(PDO::FETCH_ASSOC);// Указываем, что хотим получить данные в виде массива
            
            $result->execute();// Выполнение коменды

            $spc = array();
            $i = 1;
            while ($row = $result->fetch())
            {
              foreach ($row as $key => $value) {
                 if($id != "all") $spc[$key] = $value;
                 else {
                     $spc[$i][$key] = $value;
                 }
             }
             $i++;
         }
         return $spc;
     }
 }