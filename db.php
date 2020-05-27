<?php

// Using Medoo namespace
use Medoo\Medoo;

$database = null;

function databaseInit()
{
    global $database;

    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => FONTEFIER_MYSQL_DATABASE,
        'server' =>        FONTEFIER_MYSQL_HOST,
        'username' =>      FONTEFIER_MYSQL_USER,
        'password' =>      FONTEFIER_MYSQL_PASSWORD,
        'charset' =>       'utf8mb4',
        'collation' =>     'utf8mb4_general_ci',
    ]);
}

function formatFont(&$font)
{
    $font["height"] =  (int)$font["height"];
    $font["width"]  =  (int)$font["width"];
    $font["shared"] = (bool)$font["shared"];
}

function databaseGetFont($font_id)
{
    global $database;

    $font = $database->get("fonts", [
        "fontId", "width", "height", "name", "userId", "shared"
    ], [
        "fontId" => $font_id
    ]);

    if ($font !== null)
    {
        formatFont($font);
    }

    return $font;
}

function databaseGetFonts($userId)
{
    global $database;

    $fonts = $database->select("fonts", [
        "fontId", "width", "height", "name", "userId", "shared"
    ], [
        "userId" => $userId
    ]);

    if ($fonts !== null)
    {
        foreach ($fonts as &$font) {
            formatFont($font);
        }
    }

    return $fonts;
}

function databaseGetGlyph($fontId, $charCode)
{
    global $database;
    
    $glyph = $database->get("glyphs", "data", [
        "fontId" => $fontId,
        "charCode" => $charCode
    ]);

    return $glyph;
}
?>