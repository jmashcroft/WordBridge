<?php
/**
 * @version     $Id$
 * @package  Wordbridge
 * @copyright   Copyright (C) 2010 Cognidox Ltd
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

defined('_JEXEC') or die( 'Restricted access' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

?>
<div class="wordbridge_blog blog<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
    <div class="wordbridge_blog_header">
    <?php if ( $this->params->get( 'show_page_heading', 1 ) ) : ?>
    <div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
        <?php echo sprintf( '<a href="%s">%s</a>',
                            JRoute::_( $this->blogLink ),
                            $this->escape($this->params->get( 'page_title' ) ) ); ?>
    </div>
    <?php endif; ?>
    <?php if ( !empty( $this->blogTitle ) ): ?>
        <?php echo $this->escape( $this->blogTitle ); ?>
    <?php endif; ?>
    </div>
    <div class="wordbridge_entries">
        <?php if ($this->entries && count($this->entries)) {
            foreach ($this->entries as $entry): ?>
            <div class="wordbridge_entry">
                <h2 class="wordbridge_title contentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
                <?php echo sprintf( '<a href="%s">%s</a>', 
                        JRoute::_( $this->blogLink . '&p=' . $entry['postid'] .
                                   '&slug=' . $entry['slug'] . '&view=entry' ),
                        $this->escape( $entry['title'] ) ); ?>
                </h2>
                <span class="wordbridge_date"><?php echo JFactory::getDate( $entry['date'] )->toFormat( '%B %e, %Y', true ); ?></span>
                <div class="wordbridge_content">
                <?php 
                    $blogContent = $entry['content'];
                    if ( $this->params->get( 'wordbridge_show_links' ) == 'no' )
                    {
                        $br_pos = strrpos( $entry['content'], '<br />' );
                        if ( $br_pos > 0 )
                        {
                            $blogContent = substr( $entry['content'], 0, $br_pos );
                        }
                    }
                    // Look for more-link
                    if ( preg_match( '/^(.+?)<span\s+id="more-(\d+)"><\/span>(.*)/is', $blogContent, $matches ) )
                    {
                        $blogContent = $matches[1];
                        $blogContent .= sprintf( '<a href="%s#more-%s">%s</a>',
                                                 JRoute::_( $this->blogLink . '&p=' . $entry['postid'] .
                                                            '&slug=' . $entry['slug'] . '&view=entry' ),
                                                 $matches[2], JText::_( 'COM_WORDBRIDGE_READ_THE_REST' ) );
                        // Care needs to be taken to allow closing tags from 
                        // earler in the content to close.
                        // Strip closed block elements, which should just
                        // leave closing elements
                        $parts = preg_split( '/(<[^>]+>)/s', $matches[3], -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE );
                        $extra = '';
                        $last_tag = false;
                        foreach ( $parts as $part )
                        {
                            // Skip non HTML things
                            if ( strpos( $part, '<' ) !== 0 )
                            {
                                continue;
                            }
                            if ( strpos( $part, '/' ) === 1 )
                            {
                                // This is a closing tag. If last_tag is
                                // set we'll skip it if its not matching 
                                // last tag. If it does match, unset last_tag
                                // and skip
                                if ( $last_tag )
                                {
                                    if ( preg_match( '/<\/' . preg_quote( $last_tag ) . '\s*/', $part ) )
                                    {
                                        $last_tag = false;
                                    }
                                    continue;
                                }
                            }
                            else if ( $last_tag )
                            {
                                // we already have an open tag, so we
                                // can skip any openers
                                continue;
                            }
                            else
                            {
                                // Opening html element set last_tag
                                // and skip
                                if ( preg_match( '/<([A-Za-z]+)/', $part, $matches ) )
                                    $last_tag = $matches[1];
                                continue;
                            }
                            $blogContent .= $part;
                        }
                    }
                    echo $blogContent;
                ?>
                </div>

                <?php if ( !empty( $entry['categories'] ) ): ?>
                <div class="wordbridge_categories">
                    <?php
                        $categoryLinkList = array();
                        foreach ( $entry['categories'] as $category )
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
        <?php endforeach; } ?>

        <?php if ( !empty( $this->olderLink ) || !empty( $this->newerLink ) ): ?>
            <div class="wordbridge_nav">
                <?php if ( !empty( $this->olderLink ) ): ?>
                    <span class="wordbridge_older">
                        <?php echo sprintf( '<a href="%s">%s</a>',
                                            JRoute::_( $this->olderLink ),
                                            JText::_( 'COM_WORDBRIDGE_OLDER_ENTRIES' ) ); ?>
                    </span>
                <?php endif; ?>
                <?php if ( !empty( $this->newerLink ) ): ?>
                    <span class="wordbridge_newer">
                        <?php echo sprintf( '<a href="%s">%s</a>',
                                        JRoute::_( $this->newerLink ),
                                        JText::_( 'COM_WORDBRIDGE_NEWER_ENTRIES' ) ); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
