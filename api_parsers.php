<?php

function api_empty_endpoint(&$responce)
{
    $responce["ok"]            = false;
    $responce["error_code"]    = API_ERROR_EMPTY_QUERY;
    $responce["http_code"]     = 501;
    $responce["description"]   = "Please specify the requested endpoint";
}

function api_unsupported_endpoint($endpoint, &$responce)
{
    $responce["ok"]            = false;
    $responce["error_code"]    = API_ERROR_ENDPOINT_UNSUPPORTED;
    $responce["http_code"]     = 501;
    $responce["description"]   = "Endpoint `" . $endpoint . "` is not supported";
}

function api_parse_glyph($request, &$responce, &$database)
{
    $request_method = $_SERVER['REQUEST_METHOD'];

    if ($request_method == "POST")
    {
        $responce["ok"]            = false;
        $responce["error_code"]    = API_ERROR_METHOD_UNSUPPORTED;
        $responce["http_code"]     = 501;
        $responce["description"]   = "Method `" . $request_method . 
            "` is not allowed for endpoint `" . $request[1] . "`";
    }
    else
    {
        $font_id = $_GET["font_id"];
        $char_code = $_GET["char_code"];

        if (($font_id == "") || ($char_code == ""))
        {
            $responce["ok"]            = false;
            $responce["error_code"]    = API_ERROR_TOO_FEW_ARGUMENTS;
            $responce["http_code"]     = 400;
            $responce["description"]   = "Both `font_id` and `char_code` are required";
            
            return false;
        }

        $glyph = $database->get("glyphs", "data", [
            "font" => $font_id,
            "charCode" => $char_code
        ]);

        if ($glyph === null)
        {
            $responce["ok"]            = false;
            $responce["error_code"]    = API_ERROR_GLYPH_NOT_FOUND;
            $responce["http_code"]     = 404;
            $responce["description"]   = "The requested glyph was not found";

            return false;
        }

        $responce["ok"] = true;

        return json_decode($glyph, true);
    }
}

?>