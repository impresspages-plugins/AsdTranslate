<?php

namespace Plugin\AsdTranslate;

class AdminController extends \Ip\Controller {
    public function index()
    {
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
        }
        return new \Ip\Response\Json($currentTranslation);
    }
}
