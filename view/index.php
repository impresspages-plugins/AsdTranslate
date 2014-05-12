<div class="ipPluginAsdTranslate">
    <div class="row">
        <div class="col col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <?php $languages = ipContent()->getLanguages(); ?>
            <ul>
                <?php foreach( $languages as $language ): ?>
                    <li class="<?php echo $query['language'] == $language->code ? 'active' : ''; ?>"><a href="?aa=AsdTranslate&language=<?php echo $language->code; ?>"><?php echo $language->longDescription; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php if( !empty( $query['language'] ) ): ?>
            <div class="col col-xs-6 col-sm-6 col-md-3 col-lg-2">
                <ul>
                    <?php if( !empty( $plugins ) ): ?>
                        <li class="title"><?php echo __( 'Plugins', 'AsdTranslate' ); ?></li>
                        <?php foreach( $plugins as $plugin ): ?>
                            <li class="<?php echo $query['plugin'] == $plugin[0] ? 'active' : ''; ?>"><a href="?aa=AsdTranslate&language=<?php echo $query['language']; ?>&plugin=<?php echo $plugin[0]; ?>"><?php echo $plugin[1]; ?></a></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if( !empty( $themes ) ): ?> 
                        <li class="title"><?php echo __( 'Themes', 'AsdTranslate' ); ?></li>
                        <?php   foreach( $themes as $theme ): ?>
                            <li class="<?php echo $query['theme'] == $theme ? 'active' : ''; ?>"><a href="?aa=AsdTranslate&language=<?php echo $query['language']; ?>&theme=<?php echo $theme; ?>"><?php echo $theme; ?></a></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col col-xs-12 col-sm-12 col-md-7 col-lg-8">
                <?php if( !empty( $query['theme'] ) ) { $type = 'theme'; } elseif( !empty( $query['plugin'] ) ) { $type = 'plugin'; } else { $type = null; }  ?>
                <?php if( !empty( $writable ) ): ?>
                    <p class="alert alert-danger"><?php echo $writable; ?></p>
                <?php endif; ?>
                <?php if( !empty( $results ) ): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo __( 'String', 'AsdTranslate' ); ?></th>
                            <th><?php echo __( 'Current translation', 'AsdTranslate' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach( $results as $translate => $translation ): ?>
                        <tr>
                            <td><?php echo $translate; ?></td>
                            <td>
                                <?php if( empty( $writable ) ): ?>
                                    <?php echo ipSlot( 'translate', array( 'translate' => $translate, 'translation' => $translation, 'type' => $type, 'name' => $query[$type], 'language' => $query['language'] ) );?>
                                <?php else: ?>
                                    <?php echo $translation; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php elseif( !empty( $type ) ): ?>
                    <p class="alert alert-warning"><?php echo __( "Didn't find any translatable strings", 'AsdTranslate', false ); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
