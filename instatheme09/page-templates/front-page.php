<?php
/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 */
get_header(); ?>
	<div class="span12">
        <div class="bgwhite">
        <?php if(is_front_page()) : ?>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php 
				// Get the content
				$content = get_the_content();			
				if(!trim($content) == "") // Check if the string is empty or only whitespace
				{        
				?>
                      <div class="row-fluid">
                        <div class="span12">
                            <div class="hp-content module"> 	
                            <?php the_content(); ?>
                            </div>
                        </div>
                      </div>
                <?php } ?>
         <?php endwhile; endif; ?>
        <?php endif; ?>
        <?php if ( is_active_sidebar( 'insta-left-home' ) || is_active_sidebar( 'insta-right-home' ) ) : ?>	
        <div class="row-fluid">
            <div class="row-fluid">
                <div class="span3">
                <?php if ( is_active_sidebar( 'insta-left-home' ) ) : ?>		
                    <?php dynamic_sidebar( 'insta-left-home' ); ?>	
                <?php endif; ?>
                </div>
                <div class="span9">
                    <?php if ( is_active_sidebar( 'insta-right-home' ) ) : ?>		
                        <?php dynamic_sidebar( 'insta-right-home' ); ?>		
                    <?php endif; ?>            
                </div>
            </div>
        </div>
        <?php endif; ?>  
        <?php if ( is_active_sidebar( 'insta-bottom-full-home' ) ) : ?>		
            <div class="row-fluid">
                    <?php dynamic_sidebar( 'insta-bottom-full-home' ); ?>
            </div>
       	<div class="home-separator"></div>
        <div class="clearfix"></div>
         <?php endif; ?> 
        <?php if ( is_active_sidebar( 'insta-bottom-left-home' ) && is_active_sidebar( 'insta-bottom-middle-home' ) && is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>
        <div class="row-fluid">        
            <div class="span4">        
                <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
            </div>
            <div class="span4">        		
                <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
            </div>
            <div class="span4">
                <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>
            </div>
        </div>    
        <?php else: ?> 
           
        <?php if ( is_active_sidebar( 'insta-bottom-left-home' ) ) : ?>
        <div class="home-separator"></div>
        <div class="row-fluid">        
            <?php if ( !is_active_sidebar( 'insta-bottom-middle-home' ) && !is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>        
                <div class="span12">        
                    <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
                </div>        
            <?php else: ?>        
                <div class="span6">        		
                    <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
                </div>
                <div class="span6">        		
                    <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
                    <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>        
                </div>               
            <?php endif; ?>
        </div>
        <?php else: ?>
            
            <?php if ( is_active_sidebar( 'insta-bottom-middle-home' ) ) : ?>
            <div class="row-fluid">            
            <?php if ( !is_active_sidebar( 'insta-bottom-left-home' ) && !is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>
                <div class="span12">        		
                    <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
                </div>        
            <?php else: ?>
                <?php if ( is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>
                <div class="span6">        		
                    <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
                </div>            
                <div class="span6">
                    <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>        
                </div>
                <?php else: ?>
                <div class="span6">        		
                    <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
                </div>            
                <div class="span6">
                    <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>        
                </div>
                <?php endif; ?>
            <?php endif; ?>
                
            </div>
            <?php else: ?>
            
            <?php if ( is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>
            <div class="row-fluid">            
                <?php if ( !is_active_sidebar( 'insta-bottom-left-home' ) && !is_active_sidebar( 'insta-bottom-middle-home' ) ) : ?>
                <div class="span12">        		
                    <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>
                </div>
                <?php else: ?>
                <div class="span3">        		
                    <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>
                </div>            
                <div class="span9">        		
                    <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
                    <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>        
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php endif; ?>
        
        <?php endif; ?>
        <?php endif; ?>	  
           </div>
	</div>
<?php get_footer(); ?>