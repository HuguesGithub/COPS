<?php
namespace core\bean;

use core\bean\TableauBodyHtmlBean;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe PaginationHtmlBean
 * @author Hugues
 * @since v1.23.06.10
 * @version v1.23.07.29
 */
class PaginationHtmlBean extends UtilitiesBean
{
    private $nbPerPage;
    private $option;
    private $nbElements;
    private $nbPages;
    private $curPage;
    private $queryArg;
    private $objs;
    private $url;
    private $pageWidth;

    /**
     * @since v1.23.06.10
     * @version v1.23.07.29
     */
    public function setData(array $arrData): void
    {
        /////////////////////////////////////////////////
        // Paramètres optionnels
        // Si on veut personnaliser le nombre de lignes par page
        $this->nbPerPage = $arrData[self::PAGE_NBPERPAGE] ?? self::PAGE_DEFAULT_NBPERPAGE;
        // Le visuel des boutons de pagination
        $this->option = $arrData[self::PAGE_OPTION] ?? self::PAGE_OPT_FULL_NMB;
        // Le nombre de pages autour de la page courante
        $this->pageWidth = $arrData['pageWidth'] ?? 2;
        /////////////////////////////////////////////////

        /////////////////////////////////////////////////
        // Paramètres obligatoires
        // On récupère la liste des objets de la pagination.
        // On défini le nombre d'éléments et le nombre de pages
        $this->objs = $arrData[self::PAGE_OBJS] ?? [];
        $this->nbElements = count($this->objs);
        $this->nbPages = ceil($this->nbElements/$this->nbPerPage);

        $curPage = $arrData[self::CST_CURPAGE] ?? 1;
        $this->curPage = max(1, min($curPage, $this->nbPages));

        $queryArg = $arrData[self::PAGE_QUERY_ARG] ?? [];
        $queryArg[self::CST_CURPAGE] = $this->curPage;
        $this->queryArg = $queryArg;

        $this->url = $arrData[self::CST_URL] ?? '';
        /////////////////////////////////////////////////
    }

    /**
     * @since v1.23.06.10
     * @version v1.23.06.11
     */
    public function getDisplayedRows(TableauBodyHtmlBean &$objBody, TableauRowHtmlBean $objRow=null): void
    {
        $objs = array_slice($this->objs, ($this->curPage-1)*$this->nbPerPage, $this->nbPerPage);
        if (empty($this->objs) || empty($objs)) {
            $objBody->addRow($objRow);
        } else {
            while (!empty($objs)) {
                $obj = array_shift($objs);
                $objBody->addRow($obj->getBean()->getTableRow());
            }
        }
    }

    /**
     * @since v1.23.06.10
     * @version v1.23.07.29
     */
    public function getPaginationBlock(): string
    {
        if ($this->nbPages<=1) {
            return '';
        }

        // Selon l'option choisie, on affiche une pagination plus ou moins enrichie.
        $ulContent = '';
        // Met-on les numéros ?
        if (in_array(
            $this->option,
            [self::PAGE_OPT_NUMBERS, self::PAGE_OPT_SMP_NMB, self::PAGE_OPT_FULL_NMB, self::PAGE_OPT_FST_LAST_NMB]
        )) {
            $ulContent .= $this->getPaginationLink($this->curPage==1, 1, 1);
            if ($this->curPage-$this->pageWidth>2) {
                $ulContent .= $this->getPaginationLink(true, 0, '...');
            }
            $start = max($this->curPage-$this->pageWidth, 2);
            $end = min($this->curPage+$this->pageWidth, $this->nbPages-1);
            for ($i=$start; $i<=$end; $i++) {
                $ulContent .= $this->getPaginationLink($this->curPage==$i, $i, $i);
            }
            if ($this->curPage+$this->pageWidth<$this->nbPages-1) {
                $ulContent .= $this->getPaginationLink(true, 0, '...');
            }
            $ulContent .= $this->getPaginationLink($this->curPage==$this->nbPages, $this->nbPages, $this->nbPages);
        }

        // Met-on previous et next ?
        if (in_array(
            $this->option,
            [self::PAGE_OPT_SIMPLE, self::PAGE_OPT_SMP_NMB, self::PAGE_OPT_FULL, self::PAGE_OPT_FULL_NMB]
        )) {
            ////////////////////////////////////////////////////////////////////////////
            // Lien vers la page précédente. Seulement si on n'est pas sur la première.
            $strToPrevious = $this->getPaginationLink($this->curPage<2, $this->curPage-1, self::PAGE_PREVIOUS);
            ////////////////////////////////////////////////////////////////////////////
            // Lien vers la page suivante. Seulement si on n'est pas sur la dernière.
            $strToNext = $this->getPaginationLink($this->curPage>=$this->nbPages, $this->curPage+1, self::PAGE_NEXT);

            $ulContent = $strToPrevious.$ulContent.$strToNext;
        }

        // Met-on first et last ?
        if (in_array(
            $this->option,
            [self::PAGE_OPT_FULL, self::PAGE_OPT_FULL_NMB, self::PAGE_OPT_FST_LAST_NMB]
        )) {
            ////////////////////////////////////////////////////////////////////////////
            // Lien vers la première page. Seulement si on n'est ni sur la première, ni sur la deuxième page.
            $strToFirst = $this->getPaginationLink($this->curPage<3, 1, self::PAGE_FIRST);
            ////////////////////////////////////////////////////////////////////////////
            // Lien vers la dernière page. Seulement si on n'est pas sur la dernière, ni l'avant-dernière.
            $strToLast = $this->getPaginationLink($this->curPage>=$this->nbPages-1, $this->nbPages, self::PAGE_LAST);

            $ulContent = $strToFirst.$ulContent.$strToLast;
        }

        $strClass = 'pagination pagination-sm justify-content-end mb-0';
        $navContent = HtmlUtils::getBalise(self::TAG_UL, $ulContent, [self::ATTR_CLASS => $strClass]);
        $navAttributes = [self::ATTR_ARIA => [self::TAG_LABEL => 'Pagination liste']];
        return HtmlUtils::getBalise(self::TAG_NAV, $navContent, $navAttributes);
    }

    /**
     * @since v1.23.06.10
     * @version v1.23.07.22
     */
    private function getPaginationLink(bool $isDisabled, int $curpage, string $label): string
    {
        if ($isDisabled) {
            $href = '#';
            $addClass = ' '.self::CST_DISABLED;
        } else {
            $this->queryArg[self::CST_CURPAGE] = $curpage;
            $href = $this->getQueryArg();
            $addClass = '';
        }

        $strLink = HtmlUtils::getLink($label, $href, 'page-link');
        return HtmlUtils::getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>'page-item'.$addClass]);
    }

    /**
     * @since v1.23.06.10
     * @version v1.23.07.29
     */
    public function getQueryArg(): string
    {
        foreach ($this->queryArg as $key=>$value) {
            if ($value=='%') {
                unset($this->queryArg[$key]);
            }
        }
        return add_query_arg($this->queryArg, $this->url);
        /**
         * TODO
         * A valider
        if (isset($this->queryArg[self::CST_CURPAGE])) {
            return UrlUtils::getPublicUrl($this->queryArg);
        } else {
            return add_query_arg($this->queryArg, $this->url);
        }
         */
    }

}
