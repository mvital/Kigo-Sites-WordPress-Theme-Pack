<?php get_header() ?>
<div class="market-areas-main-landing">
    <div class="page-width">


        <!-- Map -->
        <div class="primary-stroke-color market-areas-map">
            <div class="map-box">
                <div class="close-map">
                    <b class="fa"></b>
                    <b class="fa"></b>
                </div>
                <div class="map"></div>
            </div>
        </div>

        <!-- Title -->
        <h1 class="title"><?php echo $title; ?></h1>

        <!--Description and map-->
        <div class="desc-and-map">
            <!-- Content -->
            <div class="pcontent">
                <?php echo $content; ?>
            </div>

            <!-- Map PopUp --> 
            <?php
            /*
              <div class="map-popup">
              <span>
              <?php echo $this->r('Map') . ' ' . $this->r('Search'); ?>
              <span class="popup-icon">
              <b class="fa"></b>
              <b class="fa"></b>
              </span>
              </span>
              <div class="mini-map">

              </div>
              </div>
             */
            ?>
        </div>

        <!-- Listing -->
        <div class="market-areas-listing">

            <?php
            foreach ($market_areas as $market_area): $bgcolor = "#333";
                if (!$market_area->props)
                    continue;
                if ($market_area->children):
                    ?>
                    <!--Root Market Area-->
                    <div class="zp-mkta">


                        <!--Title-->

                        <h2><?php echo apply_filters('the_title', $market_area->post_title); ?> <a href="<?php echo $market_area->guid; ?>" class="ma-link">(<?php echo $market_area->props; ?>)</a></h2>


                        <div class="subareas">
                            <?php
                            $children = $this->flatten_if_one_child($market_area->children);
//                             $children = $market_area->children;
                            ?>
                            <?php foreach ($children as $mkta_child): ?>


                                <?php
                                if (!$mkta_child->props)
                                    continue;

                                $location = $mkta_child->location;
                                $props = $mkta_child->props;
                                ?>
                                <a href="<?php echo $mkta_child->guid; ?>" class="ma-link">
                                    <div class="market-area-box" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url(<?php echo wp_get_attachment_url(get_post_thumbnail_id($mkta_child->ID)); ?>); background-color: <?php echo $bgcolor; ?>;">
                                        <div class="titles-box">
                                            <h4><?php echo apply_filters('the_title', $mkta_child->post_title); ?></h4>
                                            <h5><?php echo $location; ?></h5>
                                            <p><?php echo $props . ' ' . $this->r('Properties'); ?></p>
                                        </div>
                                    </div>
                                </a>


                        <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                         <div class="zp-mkta">


                        <!--Title-->

                        
                        
                        <a href="<?php echo $market_area->guid; ?>" class="ma-link">
                                    <div class="market-area-box" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url(<?php echo wp_get_attachment_url(get_post_thumbnail_id($market_area->ID)); ?>); background-color: <?php echo $bgcolor; ?>;">
                                        <div class="titles-box">
                                            <h4><?php echo apply_filters('the_title', $market_area->post_title); ?></h4>
                                            <h5><?php echo $location; ?></h5>
                                            <p><?php echo $props . ' ' . $this->r('Properties'); ?></p>
                                        </div>
                                    </div>
                                </a>

                        
                    <?php endif; ?>




                </div>
<?php endforeach; ?>
        </div>

        <script type="text/javascript">
            // All properties ids;
            var all_market_areas = <?php echo $all_market_areas; ?>;
        </script>
    </div>
</div>
<?php get_footer() ?>