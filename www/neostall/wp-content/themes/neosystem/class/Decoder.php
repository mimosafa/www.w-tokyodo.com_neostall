<?php

class Decoder {

    function getJsonToHtmlString($jsondata_string) {
        return $this -> getArrayToHtmlString(json_decode($jsondata_string, TRUE));
    }

    function getArrayToHtmlString($arraydata_array) {
        /*
         * $createDomDomelement($dom_array)
         *
         * dom_arrayから、element, attribute, textを基にdomを生成、
         * childrenが存在している場合再帰させ、dhildren要素を作成後にdomへ子要素として追加
         */
        $getDomDomelement = function($dom_array, $root_domdocument) use (&$getDomDomelement) {
            $domelement_domelement = NULL;

            if (isset($dom_array['element'])) {
                if (isset($dom_array['text']))
                    $domelement_domelement = $root_domdocument -> createElement($dom_array['element'], $dom_array['text']);
                else
                    $domelement_domelement = $root_domdocument -> createElement($dom_array['element']);

                if (isset($dom_array['attribute'])) {
                    foreach ($dom_array['attribute'] as $attributekey_string => $attributevalue_string) {
                        $domattr_domdocument = $root_domdocument -> createAttribute($attributekey_string);
                        $domattr_domdocument -> value = $attributevalue_string;

                        $domelement_domelement -> appendChild($domattr_domdocument);
                    }
                }

                if (isset($dom_array['children'])) {
                    foreach ($dom_array['children'] as $childrendom_array)
                        $domelement_domelement -> appendChild($getDomDomelement($childrendom_array, $root_domdocument));
                }
            }

            if (isset($domelement_domelement))
                $root_domdocument -> appendChild($domelement_domelement);

            return $domelement_domelement;
        };

        $main = function($arraydata_array) use ($getDomDomelement) {
            $root_domdocument = new DOMDocument();

            if (is_array($arraydata_array))
                foreach ($arraydata_array as $value) {
                    if (isset($value['element'])) {
                        $root_domdocument -> appendChild($getDomDomelement($value, $root_domdocument));
                    }
                }

            return $root_domdocument;
        };

        return $main($arraydata_array) -> saveHTML();
        ;
    }

}