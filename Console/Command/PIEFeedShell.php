<?php 
/**
 * PIEFeedShell
 *
 * Implements a CakePHP Shell that takes a CSV file as input (see README for expected fields),
 * and outputs an XML file conforming to BazaarVoice PIEFeed schema.
 *
 * Schema: http://www.bazaarvoice.com/xs/PRR/PostPurchaseFeed/5.6
 * Feed Docs: https://github.com/bazaarvoice/HostedUIResources/blob/master/PIEFeed/ExamplePIEFeed.xml
 *
 * PHP version 5
 *
 * @category Console
 * @package  BazaarVoice
 * @author   Michael Clawson <mclawson@blendtec.com>
 * @link     https://github.com/Blendtec/bazaar-voice-cakephp
 */
App::uses('File', 'Utility');
App::uses('Xml', 'Utility');

/**
 * PIEFeedShell
 *
 * Implements a CakePHP Shell that takes a CSV file as input (see README for expected fields),
 * and outputs an XML file conforming to BazaarVoice PIEFeed schema.
 *
 * Schema: http://www.bazaarvoice.com/xs/PRR/PostPurchaseFeed/5.6
 * Feed Docs: https://github.com/bazaarvoice/HostedUIResources/blob/master/PIEFeed/ExamplePIEFeed.xml
 *
 * @category Console
 * @package  BazaarVoice
 * @author   Michael Clawson <mclawson@blendtec.com>
 * @link     https://github.com/Blendtec/bazaar-voice-cakephp
 */
class PIEFeedShell extends AppShell {

/**
 * empty xml array to be filled and sent to Xml::fromArray()
 *
 * @var array
 */
	protected $_emptyPIEFeedDoc = array(
		'Feed' => array(
			'xmlns:' => 'http://www.bazaarvoice.com/xs/PRR/PostPurchaseFeed/5.6',
			'Interaction' => array()
		)
	);

/**
 * getOptionParser override
 *
 * this enables the --help option
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(
			array(
				'PIEFeeds csv to PIE xml feed converter.',
				'Please see the README.md for the expected fields in the csv.'
			)
		)->addArgument(
			'infile',
			array('help' => 'csv input file', 'required' => true)
		)->addArgument(
			'outfile',
			array('help' => 'xml output file', 'required' => true)
		);
		return $parser;
	}

/**
 * main
 *
 * we only do one thing so it is in main
 *
 * @return void
 */
	public function main() {
		//check if infile exists
		if (empty($this->args[0])) {
			$this->out('infile required');
			return;
		}
		$inFilePath = $this->args[0];
		if (!file_exists($inFilePath)) {
			$this->out('infile doesn\'t exist.');
			return;
		}
		//check if out file is writable
		if (empty($this->args[1])) {
			$this->out('outfile required');
			return;
		}
		$outFilePath = $this->args[1];
		$outFile = new File($outFilePath, true);
		if (!$outFile->writable()) {
			$this->out('outfile must be writable');
			return;
		}
		//import csv
		if (($inFile = fopen($inFilePath, 'r')) === false) {
			$this->out('Could\'t open infile for importing.');
			return;
		}
		//get header
		$data = fgetcsv($inFile);
		if ($data !== false) {
			$columns = $data;
			if (empty($columns) || $columns[0] == null) {
				$this->out('Could\'t read header columns');
				return;
			}
		}

		//import records
		$records = array();
		while (($data = fgetcsv($inFile)) !== false) {
			$record = $this->_buildRawRecord($columns, $data);
			if ($record === false) {
				continue;
			}
			if (!empty($records[$record['order_number']])) {
				if (isset($records[$record['order_number']][0])) {
					$records[$record['order_number']][] = $record;
				} else {
					$first = $records[$record['order_number']];
					$records[$record['order_number']] = array($first, $record);
				}
			} else {
				$records[$record['order_number']] = $record;
			}
		}
		fclose($inFile);

		//export records
		//build xml array
		$feed = $this->_emptyPIEFeedDoc;
		foreach ($records as $record) {
			$interaction = $this->_buildInteraction($record);
			$feed['Feed']['Interaction'][] = $interaction;
		}
		//output xml
		$xml = Xml::fromArray($feed);
		$xml->asXML($outFilePath);
	}

/**
 * protected buildRawRecord
 *
 * build associative array from csv data row and column names
 *
 * @param array $columns CSV columns
 * @param array $data    data from csv row
 *
 * @return array
 */
	protected function _buildRawRecord($columns, $data) {
		$record = array();

		$count = count($columns);
		for ($i = 0; $i < $count; $i++) {
			if (is_null($data[$i]) || strtolower($data[$i]) == 'null') {
				return false;
			}
			$record[$columns[$i]] = $data[$i];
		}
		return $record;
	}

/**
 * protected buildInteraction
 *
 * build an array compatible with Xml::fromArray() that will output
 * the Interaction tag properly according to the schema.
 *
 * @param array $columns CSV columns
 * @param array $data    data from csv row
 *
 * @return array
 */
	protected function _buildInteraction($record) {
		if (isset($record[0])) {
			$items = $record;
			$record = $record[0];
		} else {
			$items = array($record);
		}
		$interaction = array();
		$interaction['EmailAddress'] = $record['email'];
		$interaction['TransactionDate'] = date('c', strtotime($record['order_date']));
		if (isset($record['locale'])) {
			$interaction['Locale'] = $record['locale'];
		}
		if (isset($record['user_name'])) {
			$interaction['UserName'] = $record['user_name'];
		}
		if (isset($record['user_id'])) {
			$interaction['UserID'] = $record['user_id'];
		}
		$interaction['Products'] = array('Product' => array());
		foreach ($items as $item) {
			$product = array();
			$product['ExternalId'] = $item['product_external_id'];
			if (isset($item['product_name'])) {
				$product['Name'] = $item['product_name'];
			}
			if (isset($item['product_image_url'])) {
				$product['ImageUrl'] = $item['product_image_url'];
			}
			if (isset($item['product_price'])) {
				$product['Price'] = $item['product_price'];
			}
			$interaction['Products']['Product'][] = $product;
		}
		return $interaction;
	}
}