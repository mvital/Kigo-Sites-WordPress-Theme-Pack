<?php

use Kigo\Themes\instaparent\XBAPI;

$xbapi = new XBAPI();
$specialOffers = $xbapi->getSpecialOffers($ins['special_offers']);
//    dd($pfinders, true);
?>
<?php if(!empty($ins['title'])): ?>
<div class="widget-title">
    <h1><?php echo $ins['title']; ?></h1>
</div>
<?php endif; ?>
<div>

    <div class="kigo-spoffers-items">
        <?php foreach ($specialOffers as $spoffer) {
            ?>
            <div class="kigo-spoffer width<?php echo $ins['items_per_row']; ?>">

                    <div class="kigo-spoffer-content">
                        <a href="<?php echo $spoffer->ContextData->SEO->DetailURL; ?>">
                        <div class="kigo-spoffer-image" style="background-image: url(<?php echo $spoffer->Images[0]->{'MediumURL'} ?>)"></div>
                        </a>

                        <span class="kigo-spoffer-name"><a href="<?php echo $spoffer->ContextData->SEO->DetailURL; ?>"><?php echo $spoffer->Name ?></a></span>
                        <hr>
                        <div class="kigo-spoffer-description">
                            <?php echo $spoffer->Description; ?>
                        </div>

                    </div>

            </div>
        <?php }
        ?>
    </div>
