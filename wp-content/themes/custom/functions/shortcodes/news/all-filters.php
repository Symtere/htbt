<div class="filtres-filters">

    <!-- Reset filter -->
    <div class="reset-selects-filter">
        <?php if ($a['alfs'] == 'actif') : ?>
            <div id="filters-selects">
            </div>
        <?php endif ?>

        <ul id="reset-filter" class="list-unstyled">
            <li class="alltax">
                <a href="#" data-filter="all-terms" data-name="" data-term="all-terms" data-page="1">
                    Réinitialiser les filtres
                </a>
            </li>
        </ul>
    </div>

    <!-- Search filter -->
    <?php if ($a['fsearch'] == 'actif') : ?>
        <div class="filter-search">
            <?php if ($a['tcr']) : ?>
                <div class="title-filters"><?php echo $a['tcr']; ?></div>
            <?php endif ?>
            <div class="icone-input">
                <input placeholder="Votre recherche ..." type="text" class="spub" name="spub" id="spub">
            </div>
        </div>
    <?php endif ?>

    <!-- Date filter -->
    <?php //echo ($a['fdate'] == 'actif') ? 'style="display:none;"' : ''; ?>
    <div style="<?php if ($a['fdate'] == 'actif') {
                } else {
                    echo "display:none;";
                } ?>" class="filter-date">
        <?php if ($a['tcd']) : ?>
            <div class="title-filters"><?php echo $a['tcd']; ?></div>
        <?php endif ?>
        <div id="range"></div>
    </div>

    <!-- All other filters -->
    <?php

    foreach ($unfiltre as $thefiltre) {

        if ($thefiltre['hideempty'] == 'true') {
            $terms = get_terms(array(
                'taxonomy' => $thefiltre['taxo'],
                'hide_empty' => true,
            ));
        } else {
            $terms = get_terms(array(
                'taxonomy' => $thefiltre['taxo'],
                'hide_empty' => false,
            ));
        }

        if ($thefiltre['tdf'] == "checkbox") { ?>

            <div class="filter-distinction">

                <?php if ($thefiltre['titlefilter']) { ?>
                    <div class="title-filters"><?php echo $thefiltre['titlefilter']; ?></div>
                <?php } ?>
                <ul class="checkbox-filter list-unstyled">
                    <?php foreach ($terms as $term) : ?>
                        <li <?php if ($term->term_id == $a['active']) : ?> class="active" <?php endif; ?>>
                            <a href="" data-name="<?= $term->name; ?>" data-filter="<?= $term->taxonomy; ?>" data-term="<?= $term->slug; ?>" data-page="1">
                                <?= $term->name; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        <?php } elseif ($thefiltre['tdf'] == "select") { ?>

            <div class="filter-categories">
                <?php if ($thefiltre['titlefilter']) { ?>
                    <div class="title-filters"><?php echo $thefiltre['titlefilter']; ?></div>
                <?php } ?>
                <div class="select-filter">
                    <div class="select-all">
                        <?php //echo $thefiltre['tpd'] ? $thefiltre['tpd'] : "Choisissez..."; ?>
                        <?php if ($thefiltre['tpd']) {
                            echo $thefiltre['tpd'];
                        } else {
                            echo "Choisissez...";
                        }  ?>
                    </div>
                    <ul class="filters">
                        <li class="all-filter-select">
                            <a href="#" data-filter="allfilterselect">
                                <?php //echo $thefiltre['ttlf'] ? $thefiltre['ttlf'] : "Tout"; ?>
                                <?php if ($thefiltre['ttlf']) {
                                    echo $thefiltre['ttlf'];
                                } else {
                                    echo "Tout";
                                }  ?>
                            </a>
                        </li>
                        <?php foreach ($terms as $term) : ?>
                            <li <?php if ($term->term_id == $a['active']) : ?> class="active" <?php endif; ?>>
                                <a href="" data-name="<?= $term->name; ?>" data-filter="<?= $term->taxonomy; ?>" data-term="<?= $term->slug; ?>" data-page="1">
                                    <?= $term->name; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

        <?php } elseif ($thefiltre['tdf'] == "radio") { ?>

            <div class="filter-type">
                <?php if ($thefiltre['titlefilter']) { ?>
                    <div class="title-filters"><?php echo $thefiltre['titlefilter']; ?></div>
                <?php } ?>
                <ul class="radio-filter list-unstyled">
                    <?php foreach ($terms as $term) : ?>
                        <li <?php if ($term->term_id == $a['active']) : ?> class="active" <?php endif; ?>>
                            <a href="" data-name="<?= $term->name; ?>" data-filter="<?= $term->taxonomy; ?>" data-term="<?= $term->slug; ?>" data-page="1">
                                <?= $term->name; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

    <?php }
    } ?>
</div>
