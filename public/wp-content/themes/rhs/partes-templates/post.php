<?php global $RHSVote; ?>
<div class="col-xs-6 post">
    <div class="block">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="middle">
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo get_the_post_thumbnail_url() ?>" alt="">
                    </a>
            </div>
        <?php endif;?>

        <div class="head">
            <div class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
            <div class="info">
                <?php
                    if ( has_post_thumbnail() )
                        echo limitatexto(get_the_excerpt(), " [...]", 345);
                    else
                        echo limitatexto(get_the_excerpt(), " [...]", 1020);
                ?>
            </div>
            <div class="style">
                <div class="col-xs-3 col-md-pull-1">
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                       title="Ver o perfil do(a) <?php the_author_meta( 'display_name' ); ?>.">
                        <?php echo get_avatar(get_the_author_meta( 'ID' ) ); ?>
                    </a>
                </div>
                <div class="user_box col-xs-7 col-md-pull-1">
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
                    <p><?php the_time( 'd/m/Y' ); ?></p>
                </div>
                <div class="col-xs-2 votebox"><?php $RHSVote->get_vote_box(get_the_ID(), array('display_button'=>true)); ?></div>
            </div>
            <div class="footer">
                <div class="comments col-xs-6 col-md-pull-1">
                    <?php
                        if ( comments_open() ) :
                            comments_popup_link( '0 COMENTÁRIOS',
                                '<i class="fa fa-commenting-o" aria-hidden="true"></i> 1 COMENTÁRIO',
                                '<i class="fa fa-commenting-o" aria-hidden="true"></i> % COMENTÁRIOS', 'comments-link',
                                'Não é permitido Comentários neste post' );
                        endif;
                    ?>
                </div>
                <div class="col-xs-6">
                    <a href="#"><i class="fa fa-share-alt" aria-hidden="true"></i> COMPARTILHAR</a>
                </div>
            </div>
        </div>

    </div>
</div>