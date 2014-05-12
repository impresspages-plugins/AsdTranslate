<?php

/**
 * Admin controller for ImpressPages
 */

namespace Plugin\AsdTranslate;

class AdminController extends \Ip\Controller {
    public function index()
    {
        $data['query'] = ipRequest()->getQuery();
        if( !empty( $data['query']['language'] ) ) {
            if( !empty( $data['query']['plugin'] ) ) {
                $dir = ipFile( 'Plugin/'.$data['query']['plugin'] );
                $files = Model::get_files( $dir );
                $found = Model::get_scan_files( $files, $data['query']['plugin'] );
                $currentTranslation = Model::get_current_plugin_translation( $data['query']['plugin'], $data['query']['language'] );
                 $data['results'] = array_merge( $found, $currentTranslation );
            }
            if( !empty( $data['query']['theme'] ) ) {
                $dir = ipFile( 'Theme/'.$data['query']['theme'] );
                $files = Model::get_files( $dir );
                $found = Model::get_scan_files( $files, $data['query']['theme'] );
                $currentTranslation = Model::get_current_theme_translation( $data['query']['theme'], $data['query']['language'] );
                $data['results'] = array_merge( $found, $currentTranslation );
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
            $currentTranslation = Model::get_current_theme_translation( $data['name'], $data['language'] );
            $currentTranslation[$data['translate']] = $data['translation'];
            Model::save_translation( $currentTranslation, $data['name'], $data['language'] );
        } elseif( $data['type'] == 'plugin' ) {
            $currentTranslation = Model::get_current_plugin_translation( $data['name'], $data['language'] );
            $currentTranslation[$data['translate']] = $data['translation'];
            Model::save_translation( $currentTranslation, $data['name'], $data['language'] );
        }
        return new \Ip\Response\Json($currentTranslation);
    }
    
}
