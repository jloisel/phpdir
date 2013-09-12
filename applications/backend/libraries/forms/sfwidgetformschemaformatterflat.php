<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * 
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormSchemaFormatterTable.class.php 5995 2007-11-13 15:50:03Z fabien $
 */
class sfWidgetFormSchemaFormatterFlat extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<p style=\"display:inline;\"><b>%label%</b> &nbsp; %field%%error%%help%%hidden_fields%</p>\n",
    $errorRowFormat  = "<p>%errors%</p>\n",
    $helpFormat      = '<br />%help%',
    $decoratorFormat = "<table>\n  %content%</table>";
}
