<div class="row">
    <div class="col col-xs-2">
        <?php $languages = ipContent()->getLanguages(); ?>
        <ul>
            <?php foreach( $languages as $language ): ?>
                <li><a href="?aa=AsdTranslate&language=<?php echo $language->code; ?>"><?php echo $language->longDescription; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php if( !empty( $query['language'] ) ): ?>
        <div class="col col-xs-2">
            <ul>
                <?php if( !empty( $plugins ) ): ?>
                    <li><?php echo __( 'Plugins', 'AsdTranslate' ); ?></li>
                    <?php foreach( $plugins as $plugin ): ?>
                        <li><a href="?aa=AsdTranslate&language=<?php echo $query['language']; ?>&plugin=<?php echo $plugin[0]; ?>"><?php echo $plugin[1]; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if( !empty( $themes ) ): ?> 
                    <li><?php echo __( 'Themes', 'AsdTranslate' ); ?></li>
                    <?php   foreach( $themes as $theme ): ?>
                        <li><a href="?aa=AsdTranslate&language=<?php echo $query['language']; ?>&theme=<?php echo $theme; ?>"><?php echo $theme; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <?php if( !empty( $results ) ): ?>
            <div class="col col-xs-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo __( 'String', 'AsdTranslate' ); ?></th>
                            <th><?php echo __( 'Current translation', 'AsdTranslate' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if( !empty( $query['theme'] ) ) { $type = 'theme'; } elseif(  $query['plugin'] ) { $type = 'plugin'; }  ?>
                    <?php foreach( $results as $translate => $translation ): ?>
                        <tr>
                            <td><?php echo $translate; ?></td>
                            <td>
                                <?php echo ipSlot( 'translate', array( 'translate' => $translate, 'translation' => $translation, 'type' => $type, 'name' => $query[$type], 'language' => $query['language'] ) );?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
