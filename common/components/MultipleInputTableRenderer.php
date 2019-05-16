<?php

/**
 * @link      https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license   https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace common\components;

use unclead\multipleinput\components\BaseColumn;
use unclead\multipleinput\renderers\TableRenderer;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class TableRenderer
 * @package unclead\multipleinput\renderers
 */
class MultipleInputTableRenderer extends TableRenderer
{
    /**
     * @return mixed
     */
    protected function internalRender()
    {
        $content = [];

        if ($this->hasHeader()) {
            $content[] = $this->renderHeader();
        }

        $content[] = $this->renderBody();
        $content[] = $this->renderFooter();

        $options = [];
        Html::addCssClass($options, 'multiple-input-list table table-condensed table-renderer');

        $content = Html::tag('table', implode("\n", $content), $options);

        return Html::tag('div', $content, [
            'id'    => $this->id,
            'class' => 'multiple-input',
        ]);
    }

    /**
     * Renders the header.
     *
     * @return string
     */
    public function renderHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            $cells[] = $this->renderHeaderCell($column);
        }

        if ($this->max === null || ($this->max >= 1 && $this->max !== $this->min)) {
            $button = $this->isAddButtonPositionHeader() ? $this->renderAddButton() : '';

            $cells[] = Html::tag('th', $button, [
                'class' => 'list-cell__button',
            ]);
        }

        return Html::tag('thead', Html::tag('tr', implode("\n", $cells)));
    }

    /**
     * Renders the footer.
     *
     * @return string
     */
    public function renderFooter()
    {
        if (!$this->isAddButtonPositionFooter()) {
            return '';
        }

        $cells = [];
        if ($this->sortable)
            $cells[] = Html::tag('td', '', []);
        $cells[] = Html::tag('td', $this->renderAddButton(), [
            'class' => 'list-cell__button',
        ]);
        return Html::tag('tfoot', Html::tag('tr', implode("\n", $cells)));
    }


    /**
     * Check that at least one column has a header.
     *
     * @return bool
     */
    private function hasHeader()
    {
        if ($this->min === 0 || $this->isAddButtonPositionHeader()) {
            return true;
        }

        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            if ($column->title) {
                return true;
            }
        }

        return false;
    }

    /**
     * Renders the header cell.
     * @param BaseColumn $column
     * @return null|string
     */
    private function renderHeaderCell($column)
    {
        if ($column->isHiddenInput()) {
            return null;
        }
        $options = $column->headerOptions;
        Html::addCssClass($options, 'list-cell__' . $column->name);

        return Html::tag('th', $column->title, $options);
    }

    /**
     * Renders the body.
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\InvalidParamException
     */
    protected function renderBody()
    {
        $rows = [];

        if ($this->data) {
            $cnt = count($this->data);
            if ($this->min === $this->max && $cnt < $this->max) {
                $cnt = $this->max;
            }

            $indices = array_keys($this->data);

            for ($i = 0; $i < $cnt; $i++) {
                $index  = ArrayHelper::getValue($indices, $i, $i);
                $item   = ArrayHelper::getValue($this->data, $index, null);
                $rows[] = $this->renderRowContent($index, $item);
            }
        } elseif ($this->min > 0) {
            for ($i = 0; $i < $this->min; $i++) {
                $rows[] = $this->renderRowContent($i);
            }
        }

        return Html::tag('tbody', implode("\n", $rows));
    }

    /**
     * Renders the row content.
     *
     * @param int                         $index
     * @param ActiveRecordInterface|array $item
     * @return mixed
     * @throws InvalidConfigException
     */
    private function renderRowContent($index = null, $item = null)
    {
        $cells        = [];
        $hiddenInputs = [];
        $isLastRow    = $this->max === $this->min;
        if (!$isLastRow && $this->isAddButtonPositionRowBegin()) {
            $cells[] = $this->renderActionColumn($index, true);
        }

        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            $column->setModel($item);
            if ($column->isHiddenInput()) {
                $hiddenInputs[] = $this->renderCellContent($column, $index);
            } else {
                $cells[] = $this->renderCellContent($column, $index);
            }
        }

        if (!$isLastRow) {
            $cells[] = $this->renderActionColumn($index, false);
        }

        if ($hiddenInputs) {
            $hiddenInputs = implode("\n", $hiddenInputs);
            $cells[0]     = preg_replace('/^(<td[^>]+>)(.*)(<\/td>)$/s', '${1}' . $hiddenInputs . '$2$3', $cells[0]);
        }

        $content = Html::tag('tr', implode("\n", $cells), $this->prepareRowOptions($index, $item));

        if ($index !== null) {
            $content = str_replace('{' . $this->getIndexPlaceholder() . '}', $index, $content);
        }

        return $content;
    }

    /**
     * Prepares the row options.
     *
     * @param int                         $index
     * @param ActiveRecordInterface|array $item
     * @return array
     */
    protected function prepareRowOptions($index, $item)
    {
        if (is_callable($this->rowOptions)) {
            $options = call_user_func($this->rowOptions, $item, $index, $this->context);
        } else {
            $options = $this->rowOptions;
        }

        Html::addCssClass($options, 'multiple-input-list__item');

        return $options;
    }

    /**
     * Renders the cell content.
     *
     * @param BaseColumn $column
     * @param int|null   $index
     * @return string
     */
    public function renderCellContent($column, $index)
    {
        $id    = $column->getElementId($index);
        $name  = $column->getElementName($index);
        $input = $column->renderInput($name, [
            'id' => $id,
        ]);

        if ($column->isHiddenInput()) {
            return $input;
        }

        $hasError = false;
        $error    = '';

        if ($index !== null) {
            $error    = $column->getFirstError($index);
            $hasError = !empty($error);
        }

        if ($column->enableError) {
            $input .= "\n" . $column->renderError($error);
        }

        $wrapperOptions = [
            'class' => 'form-group field-' . $id,
        ];

        if ($hasError) {
            Html::addCssClass($wrapperOptions, 'has-error');
        }

        $input = Html::tag('div', $input, $wrapperOptions);

        return Html::tag('td', $input, [
            'class' => 'list-cell__' . $column->name,
        ]);
    }


    /**
     * Renders the action column.
     *
     * @param null|int $index
     * @param bool     $isFirstColumn
     * @return string
     */
    private function renderActionColumn($index = null, $isFirstColumn = false)
    {
        return Html::tag('td', $this->getActionButton($index, $isFirstColumn), [
            'class' => 'list-cell__button',
        ]);
    }

    private function getActionButton($index, $isFirstColumn)
    {
        if ($index === null || $this->min === 0) {
            if ($isFirstColumn) {
                return $this->isAddButtonPositionRowBegin() ? $this->renderRemoveButton() : '';
            }

            return $this->isAddButtonPositionRowBegin() ? '' : $this->renderRemoveButton();
        }

        $index++;
        if ($index < $this->min) {
            return '';
        }

        if ($index === $this->min) {
            if ($isFirstColumn) {
                return $this->isAddButtonPositionRowBegin() ? $this->renderAddButton() : '';
            }

            return $this->isAddButtonPositionRow() ? $this->renderAddButton() : '';
        }

        if ($isFirstColumn) {
            return $this->isAddButtonPositionRowBegin() ? $this->renderRemoveButton() : '';
        }

        return $this->isAddButtonPositionRowBegin() ? '' : $this->renderRemoveButton();
    }

    private function renderAddButton()
    {
        $options = [
            'class' => 'btn btn-lg btn-default multiple-input-list__btn js-input-plus',
        ];
        Html::addCssClass($options, $this->addButtonOptions['class']);

        return Html::tag('div', $this->addButtonOptions['label'], $options);
    }

    /**
     * Renders remove button.
     *
     * @return string
     * @throws \Exception
     */
    private function renderRemoveButton()
    {
        $options = [
            'class' => 'btn multiple-input-list__btn js-input-remove',
        ];
        Html::addCssClass($options, $this->removeButtonOptions['class']);

        return Html::tag('div', $this->removeButtonOptions['label'], $options);
    }

    /**
     * Returns template for using in js.
     *
     * @return string
     */
    protected function prepareTemplate()
    {
        return $this->renderRowContent();
    }
}
