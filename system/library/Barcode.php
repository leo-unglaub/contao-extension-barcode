<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Unglaub 2013
 * @author     Leo Unglaub <leo@leo-unglaub.net>
 * @package    barcode
 * @license    LGPL
 */


/**
 * Class Barcode
 * Contains methods to generate barcodes and store them as images
 *
 * Usage:
 *   You have to use this class in two steps. The first step is to
 *   generate the barcode as an image. This image gets stored in the
 *   property $strImage.
 *
 *   After that you choose between the methods "saveToFile ()" and
 *   "saveToBrowser ()". The name of the methods is program. :)
 *
 *
 * Example:
 *   // store the barcode as a file
 *   $objBarcode = new Barcode ();
 *   $objBarcode->generateCode ('12345678', '128c', 400, 500);
 *   $objBarcode->saveToFile ('system/html/mybarcode.jpg');
 *
 *   // send the barcode directly to the browser
 *   $objBarcode = new Barcode ();
 *   $objBarcode->generateCode ('12345678', '128c', 400, 500);
 *   $objBarcode->saveToBrowser ('fake-filename.jpg');
 */
class Barcode extends Controller
{
	/**
	 * All supported barcode types
	 * @var array
	 */
	protected $arrSupportedBarcodeTypes = array
	(
		'ean', 'ean13', 'ean-13', 'ean8', 'ean-8',
		'upc', 'upc-a', 'upc-e',
		'isbn',
		'39', 'code39',
		'128c', 'code128c',
		'128b', 'code128b',
		'128', 'code128',
		'128raw',
		'i25', 'interleaved 2 of 5',
		'cbr', 'codabar',
		'msi',
		'pls', 'plessy',
		'code93', '93',
	);


	/**
	 * The temporary barcode image
	 * @var string
	 */
	protected $strImage = '';



	/**
	 * Take the given command and sents it to the shell. You can override
	 * this methood if your setup requires a different command to execute
	 * ad shell command.
	 *
	 * @param	string	$strCommand		The command for the shell.
	 * @return	string					The output from the shell command.
	 */
	protected function callShellScript ($strCommand)
	{
		return shell_exec ($strCommand);
	}


	/**
	 * Generate a barcode and return the result as an image
	 *
	 * @param	string	$strCode				The value of the barcode.
	 * @param	string	$strType				The type of the barcode.
	 * @param	int		$intWidth				The width of the image.
	 * @param	int		$intHeight				The height of the image.
	 * @param	string	$strFormat				The format of the resulting image. (jpg, png)
	 * @param	string	$strAdditionalCommands	Additional commands for the shell program.
	 * @return	void
	 */
	public function generateCode
	(
		$strCode,
		$strType,
		$intWidth,
		$intHeight,
		$strFormat = 'jpg',
		$strAdditionalCommands = ''
	)
	{
		// check if the requested barcode type is supported
		if (!in_array ($strType, $this->arrSupportedBarcodeTypes))
		{
			throw new Exception ('The requested barcode type is not supported');
		}


		// start generating the shell command
		$strCommand = sprintf
		(
			'/usr/bin/barcode -e %s -b %s -g %sx%s %s | /usr/bin/convert.im6 -trim - %s:-',
			escapeshellarg ($strType),
			escapeshellarg ($strCode),
			escapeshellarg ($intWidth),
			escapeshellarg ($intHeight),
			$strAdditionalCommands,
			escapeshellarg ($strFormat)
		);


		// generate the barcode
		$this->strImage = $this->callShellScript ($strCommand);
	}


	/**
	 * Save the barcode in the given file
	 *
	 * @param	string	$strFilename	The filename of your barcode image.
	 * @return	void
	 */
	public function saveToFile ($strFilename)
	{
		$objFile = new File ($strFilename);
		$objFile->write ($this->strImage);
		$objFile->close ();
	}


	/**
	 * Send the barcode image directly to the browser.
	 *
	 * @param	string	$strFilename	The fake-filename for the download.
	 * @return	void
	 */
	public function saveToBrowser ()
	{
		throw new Exception ('Does not work because of the fucked up Contao implementation of sendFileToBrowser();');

		$strPath = uniqid ('system/tmp/barcode-');
		$this->saveToFile ($strPath);
		$this->sendFileToBrowser ($strPath);
	}
}
