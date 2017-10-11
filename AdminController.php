<?php

namespace Plugin\AsdTranslate;

class AdminController extends \Ip\Controller {
    public function index()
    {
        ipAddCss('Plugin/AsdTranslate/assets/css/style.css');
        ipAddJs('Plugin/AsdTranslate/assets/js/translate.js');

        $data['query'] = ipRequest()->getQuery();
        if( !empty( $data['query']['language'] ) ) {
            if( !empty( $data['query']['plugin'] ) ) {
                $dir = ipFile( 'Plugin/'.$data['query']['plugin'] );
                $name = $data['query']['plugin'];
                $type = 'Plugin';
            } elseif( !empty( $data['query']['theme'] ) ) {
                $dir = ipFile( 'Theme/'.$data['query']['theme'] );
                $name = $data['query']['theme'];
                $type = 'Theme';
            }
            if( !empty( $type ) ) {
                $files = Model::get_files( $dir );
                $found = Model::get_scan_files( $files, $name );
                $currentTranslation = Model::get_current_translation( $name, $data['query']['language'], $type );
                $data['results'] = array_merge( $found, $currentTranslation );
                $data['writable'] = Model::is_writable( $name, $data['query']['language'] );
            } elseif(!empty($data['query']['ip'])) {
                $ds = DIRECTORY_SEPARATOR;
                $language = $data['query']['language'];

                $originalTranslationFile = ipFile("file{$ds}translations{$ds}original{$ds}Ip-{$language}.json");

                if (!file_exists($originalTranslationFile)) {
                    $translationFile = ipFile("Ip{$ds}Internal{$ds}Translations{$ds}translations{$ds}Ip-{$language}.json");

                    // Language file not found, default to english
                    if (!file_exists($translationFile)) {
                        $translationFile = ipFile("Ip{$ds}Internal{$ds}Translations{$ds}translations{$ds}Ip-en.json");
                    }

                    copy($translationFile, $originalTranslationFile);
                }

                $found = Model::get_current_translation( 'Ip', $language, null, 'original' );
                $currentTranslation = Model::get_current_translation( 'Ip', $language, null, 'override' );

                $data['results'] = array_merge( $found, $currentTranslation );
                $data['writable'] = Model::is_writable( 'Ip', $language );
            }
        }
        $data['plugins'] = Model::get_all_plugins();
        $data['themes'] = Model::get_themes();

        $renderedHtml = ipView('view/index.php', $data )->render();
        return $renderedHtml;
    }

    public function saveTranslation() {
        $data = ipRequest()->getPost();
        if( $data['type'] == 'theme' ) {
            $currentTranslation = Model::get_current_translation( $data['name'], $data['language'], 'Theme' );
            $currentTranslation[$data['translate']] = $data['translation'];
            Model::save_translation( $currentTranslation, $data['name'], $data['language'] );
        } elseif( $data['type'] == 'plugin' ) {
            $currentTranslation = Model::get_current_translation( $data['name'], $data['language'], 'Plugin' );
            $currentTranslation[$data['translate']] = $data['translation'];
            Model::save_translation( $currentTranslation, $data['name'], $data['language'] );
        } elseif( $data['type'] == 'ip' ) {
            $currentTranslation = Model::get_current_translation( 'Ip', $data['language'], null, 'override' );
            $currentTranslation[$data['translate']] = $data['translation'];
            Model::save_translation( $currentTranslation, 'Ip', $data['language'] );
        }
        return new \Ip\Response\Json($currentTranslation);
    }
}
