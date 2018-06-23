<?php

class FormatData {

    /**
     * Format data to the expected output
     * 
     * @param  array $data
     * @return array
     */
    public static function format(array $data) {
        
        if (isset($data['headline_id'])) {
            $data['headline_id'] = md5($data['headline_id']);
        }

        if (isset($data['headline_title'])) {
            $data['headline_title'] = sprintf(
                '<span style="font-size:16px">%s</span>', 
                html_entity_decode(trim($data['headline_title']))
            );
        }

        if (isset($data['headline_img_src'])) {
            $data['headline_img_src'] = sprintf('<img width="644" height="322" src="%s">', $data['headline_img_src']);
        }

        if (isset($data['headline_img_legend'])) {
            $data['headline_img_legend'] = sprintf('<em>Légende</em> : %s', html_entity_decode(trim($data['headline_img_legend'])));
        }
        
        if (isset($data['headline_article_link'])) {
            $data['headline_article_link'] = sprintf(
                'Pour consulter l\'article, cliquez <a href="http://www.lemonde.fr/%s">ici</a>',
                $data['headline_article_link']
            );
        }
        
        return $data;
    }
}