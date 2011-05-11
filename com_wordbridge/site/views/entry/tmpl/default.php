<?php
/**
 * @version     $Id$
 * @package     Joomla
 * @subpackage  Wordbridge
 * @copyright   Copyright (C) 2010 Cognidox Ltd
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

defined('_JEXEC') or die( 'Restricted access' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

?>
<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
        <div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
        <?php echo sprintf( '<a href="%s">%s</a>',
                            JRoute::_( $this->blogLink ),
                            $this->escape($this->params->get( 'page_title' ) ) ); ?>
        </div>
<?php endif; ?>
<?php if ( !empty( $this->blogTitle ) ): ?>
    <?php echo $this->escape( $this->blogTitle ); ?>
<?php endif; ?>
    <div class="wordbridge_entry">
        <h2 class="wordbridge_title">
            <?php echo $this->escape( $this->title ); ?>
        </h2>
        <span class="wordbridge_date"><?php echo( strftime( '%B %e, %Y', $this->date ) ); ?></span>
        <?php echo $this->content; ?>
        <?php if ( !empty( $this->categories ) ): ?>
        <div class="wordbridge_categories">
        <?php
            $categoryLinkList = array();
            foreach ( $this->categories as $category )
            {
                $slug = WordbridgeHelper::nameToSlug( $category );
                $categoryLinkList[] = sprintf( '<a href="%s" class="wordbridge_category">%s</a>',
                                               $this->blogLink . '&c=' .
                                               $slug . '&view=category' .
                                               '&name=' . urlencode( $category ),
                                               $this->escape( $category ) );
            }
            echo JText::_( 'COM_WORDBRIDGE_POSTED_IN' ). ': <span class="wordbridge_categories">' .
                 implode( ', ', $categoryLinkList ) . '</span>';
        ?>
        </div>
        <?php endif; ?>
    </div>
