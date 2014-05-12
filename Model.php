<?php
/**
 * Model. Various database and form data operations.
 */
namespace Plugin\AsdTranslate;

class Model {
    public static function get_all_plugins() {
        $table = ipTable('plugin');
        $sql = "SELECT title, name FROM $table ORDER BY title ASC";
        $results = ipDb()->fetchAll($sql);
        foreach( $results as $result ) {
            $returnData[] = array( $result['name'], $result['title'] );
        }
        return $returnData;
    }
    
    public static function is_writable( $name, $language ) {
        $overrideDirPath = ipFile( "file/translations/override" );
        $overridedFilePath = $overrideDirPath . "/{$name}-{$language}.json";
        if( file_exists( $overridedFilePath ) ) {
            if( is_writable( $overridedFilePath ) ) {
                return null;
            } else {
                return sprintf( __( 'File "%s" must be writeable.', 'AsdTranslate' ), $overridedFilePath );
            }
        } elseif( is_writable( $overrideDirPath ) ) {
            return null;
        } else {
            return sprintf( __( 'Directory "%s" must be writeable.', 'AsdTranslate' ), $overrideDirPath );
        }
    }
    
    public static function save_translation( $translation, $name, $language ) {
        $overridedFilePath = ipFile( "file/translations/override/{$name}-{$language}.json" );
        file_put_contents($overridedFilePath, json_encode($translation) );
    }
    
    public static function get_current_translation( $theme, $language, $type ) {
        $translationFile = ipFile( "{$type}/{$theme}/translations/{$theme}-{$language}.json" );
        $overridedFile = ipFile( "file/translations/override/{$theme}-{$language}.json" );
        $originalTranslations = $overridedTranslations = $translations = array();
        if( file_exists( $translationFile ) ) {
            $originalJson = file_get_contents( $translationFile );
            $originalTranslations = \Ip\Internal\Design\Helper::instance()->json_clean_decode( $originalJson, true );
        }
        if( file_exists( $overridedFile ) ) {
            $overridedJson = file_get_contents( $overridedFile );
            $overridedTranslations = \Ip\Internal\Design\Helper::instance()->json_clean_decode( $overridedJson, true );
        }
        $translations = array_merge( $originalTranslations, $overridedTranslations );
        return $translations;
    }
    
    public static function get_themes() {
        $dir = ipFile( 'Theme' );
        $results = scandir( $dir );
        foreach( $results as $result ) {
            if( !in_array( $result, array( '.', '..' ) ) ) {
                if( is_dir( $dir . '/' . $result ) ) {
                    $files[] = $result;
                }
            }
        }
        return $files;
    }
    
    public static function get_files( $dir ) {
        $files = array();
        if( file_exists( $dir ) ) {
            $results = scandir($dir);
            foreach( $results as $result ) {
                if( !in_array( $result, array( '.', '..' ) ) ) {
                    $tDir = $dir . '/' . $result;

                    if( is_dir( $tDir ) ) {
                        $files = array_merge( $files, self::get_files( $tDir ) );
                    } else {
                        $ext = pathinfo($tDir, PATHINFO_EXTENSION );
                        if( $ext == 'php' ) {
                            $files[] = $tDir;
                        }
                    }
                }
            }
        }
        return $files;
    }
    
    public static function get_scan_files( $files, $name = null ) {
        $translates = array();
        if( !empty( $files ) ) {
            $patern = '/_(?:_|e)\(\s?(?:"|\')(.*?)(?:"|\')\s?,\s?(?:"|\')'.$name.'(?:"|\')\s?(?:\s?,\s?false\s?)?\)/';
            foreach( $files as $file ) {
                $handle = @fopen($file, "r");
                if ($handle) {
                    while(($buffer = fgets($handle, 4096)) !== false) {
                        $put = null;
                        preg_match_all( $patern, $buffer, $put, PREG_PATTERN_ORDER );
                        if( !empty( $put[1] ) ) {
                            foreach( $put[1] as $str ) {
                                if( empty( $translates[$str] ) ) {
                                    $translates[$str] = $str;
                                }
                            }
                        }
                    }
                    fclose($handle);
                }
            }
        }
        return $translates;
    }
} 