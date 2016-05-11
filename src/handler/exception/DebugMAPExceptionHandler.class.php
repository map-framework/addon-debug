<?php
namespace handler\exception;

use util\MAPException;
use data\file\File;
use util\Logger;
use Throwable;
use xml\Tree;
use xml\XSLProcessor;

/**
 * This file is part of the MAP-Framework.
 *
 * @author    Michael Piontkowski <mail@mpiontkowski.de>
 * @copyright Copyright 2016 Michael Piontkowski
 * @license   https://raw.githubusercontent.com/map-framework/map/master/LICENSE.txt Apache License 2.0
 */
final class DebugMAPExceptionHandler implements ExceptionHandlerInterface {

	const PATH_STYLESHEET = 'public/src/misc/xsl/debugMAPException.xsl';

	public static function handle(Throwable $exception):bool {
		if (!($exception instanceof MAPException)) {
			return false;
		}

		$tree = new Tree('exception');
		$exception->toNode($tree->getRootNode());

		Logger::error('Uncaught MAPException (see: `'.Logger::storeTree($tree, '.xml').'`)');

		echo (new XSLProcessor())
				->setStyleSheetFile(new File(self::PATH_STYLESHEET))
				->setDocumentDoc($tree->toDomDoc())
				->transform();
		return true;
	}

}