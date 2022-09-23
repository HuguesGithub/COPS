<?php
/**
 * WpPostBean
 * @author Hugues
 * @since 1.22.00.00
 * @version 1.22.09.23
 */
class WpPostBean extends UtilitiesBean
{
    /**
     * WpPost affiché
     * @var WpPost $WpPost
     */
    protected $WpPost;

    /**
     * Constructeur
     */
    public function __construct($post='')
    {
        parent::__construct();
        if ($post=='') {
            $post = get_post();
        }
        $this->WpPost = WpPost::convertElement($post);
    }

}
