<?php
namespace core\bean;

use core\bean\TableauBodyHtmlBean;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe PaginationHtmlBean
 * @author Hugues
 * @since v1.23.06.10
 * @version v1.23.07.02
 */
class PaginationHtmlBean extends UtilitiesBean
{
    private $nbPerPage = 10;
    private $orderBy = '';
    private $order = '';
    private $nbElements = 0;
    private $nbPages = 0;
    private $curPage = 1;
    private $queryArg = '';
    private $objs = [];

    private $arrOptions = [
        // - 'Previous' and 'Next' buttons only
        'simple',
        // - Page number buttons only
        'numbers',
        // - 'Previous' and 'Next' buttons, plus page numbers
        'simple_numbers',
        // - 'First', 'Previous', 'Next' and 'Last' buttons
        'full',
        // - 'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers
        'full_numbers',
        // - 'First' and 'Last' buttons, plus page numbers
        'first_last_numbers',
    ];
    private $option = 'full_numbers';

    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function setData(array $arrData): void
    {
        $this->objs = $arrData['objs'];
        $this->nbElements = count($this->objs);
        $this->nbPages = ceil($this->nbElements/$this->nbPerPage);

        $curPage = $arrData['curPage'];
        $this->curPage = max(1, min($curPage, $this->nbPages));

        $queryArg = $arrData['queryArg'];
        $queryArg[self::CST_CURPAGE] = $this->curPage;
        $this->queryArg = $queryArg;
    }

    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function getDisplayedRows(TableauBodyHtmlBean &$objBody): void
    {
        $objs = array_slice($this->objs, ($this->curPage-1)*$this->nbPerPage, $this->nbPerPage);

        while (!empty($objs)) {
            $obj = array_shift($objs);
            $objBody->addRow($obj->getBean()->getTableRow());
        }
    }

    /**
     * @since v1.23.06.10
     * @version v1.23.06.18
     */
    public function getPaginationBlock(): string
    {
        if ($this->nbPages<=1) {
            return '';
        }

        // Selon l'option choisie, on affiche une pagination plus ou moins enrichie.
        $ulContent = '';
        // Met-on les numéros ?
        if (in_array($this->option, ['numbers', 'simple_numbers', 'full_numbers', 'first_last_numbers'])) {
            for ($i=1; $i<=$this->nbPages; $i++) {
                $ulContent .= $this->getPaginationLink($this->curPage==$i, $i, $i);
            }
        }

        // Met-on previous et next ?
        if (in_array($this->option, ['simple', 'simple_numbers', 'full', 'full_numbers'])) {
            ////////////////////////////////////////////////////////////////////////////
            // Lien vers la page précédente. Seulement si on n'est pas sur la première.
            $strToPrevious = $this->getPaginationLink($this->curPage<2, $this->curPage-1, '&lsaquo;');
            ////////////////////////////////////////////////////////////////////////////
            // Lien vers la page suivante. Seulement si on n'est pas sur la dernière.
            $strToNext = $this->getPaginationLink($this->curPage>=$this->nbPages, $this->curPage+1, '&rsaquo;');

            $ulContent = $strToPrevious.$ulContent.$strToNext;
        }

        // Met-on first et last ?
        if (in_array($this->option, ['full', 'full_numbers', 'first_last_numbers'])) {
            ////////////////////////////////////////////////////////////////////////////
            // Lien vers la première page. Seulement si on n'est ni sur la première, ni sur la deuxième page.
            $strToFirst = $this->getPaginationLink($this->curPage<3, 1, '&laquo;');
            ////////////////////////////////////////////////////////////////////////////
            // Lien vers la dernière page. Seulement si on n'est pas sur la dernière, ni l'avant-dernière.
            $strToLast = $this->getPaginationLink($this->curPage>=$this->nbPages-1, $this->nbPages, '&raquo;');

            $ulContent = $strToFirst.$ulContent.$strToLast;
        }

        $strClass = 'pagination pagination-sm justify-content-end mb-O';
        $navContent = $this->getBalise(self::TAG_UL, $ulContent, [self::ATTR_CLASS => $strClass]);
        $navAttributes = [self::ATTR_ARIA => [self::TAG_LABEL => 'Pagination liste']];
        return $this->getBalise(self::TAG_NAV, $navContent, $navAttributes);
    }

    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    private function getPaginationLink(bool $isDisabled, int $curpage, string $label): string
    {
        if (!$isDisabled) {
            $this->queryArg[self::CST_CURPAGE] = $curpage;
            $href = $this->getQueryArg();
            $addClass = '';
        } else {
            $href = '#';
            $addClass = ' '.self::CST_DISABLED;
        }

        $strLink = HtmlUtils::getLink($label, $href, 'page-link');
        return $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>'page-item'.$addClass]);
  }

    /**
     * @since v1.23.06.10
     * @version v1.23.07.02
     */
    public function getQueryArg(): string
    {
        if (isset($this->queryArg['page'])) {
            return UrlUtils::getPublicUrl($this->queryArg);
        } else {
            $this->queryArg['page'] = 'hj-cops/admin_manage.php';
            $remArg = ['form', 'id'];
            return add_query_arg($this->queryArg, remove_query_arg($remArg, 'https://cops.jhugues.fr/wp-admin/admin.php'));
        }
    }

}
