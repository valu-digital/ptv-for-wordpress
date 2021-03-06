<?php
/**
 * Class PTV_Post_Type_Helper_Test
 *
 * @package PTV_FOR_WORDPRESS
 */

require_once( PTV_FOR_WORDPRESS_DIR . '/vendor/autoload.php' );
require_once( PTV_FOR_WORDPRESS_DIR . '/lib/functions.php' );
require_once( PTV_FOR_WORDPRESS_DIR . '/lib/common/class-ptv-post-type-helper.php' );
require_once( PTV_FOR_WORDPRESS_DIR . '/lib/class-ptv-common-module.php' );


/**
 * Sample test case.
 */
class Test_PTV_Post_Type_Helper extends WP_UnitTestCase {

	/**
	 * Test location channel
	 */
	function test_prepare_service() {

		$post_type_helper = new PTV_Post_Type_Helper();

		$post_type_helper->set_serializer( new PTV_Service_Serializer() );

		$expected =
			array(
				'_ptv_id'                              => '57f274dc-d837-4791-8ba0-3c845af1bf4b',
				'_ptv_type'                            => 'PermissionAndObligation',
				'_ptv_funding_type'                    => 'MarketFunded',
				'_ptv_name'                            => 'Testipalvelu FI',
				'_ptv_area_type'                       => 'AreaType',
				'_ptv_areas'                           =>
					array(
						array(
							'type'         => 'Municipality',
							'municipality' =>
								array(
									0 => '018',
									1 => '010',
								),
						),
					),
				'_ptv_processing_time_additional_info' => 'Määräaika FI',
				'_ptv_service_user_instruction'        => 'Toimintaohjeet FI',
				'_ptv_description'                     => 'Muokattu kuvaus FI',
				'_ptv_short_description'               => 'Tiivistelmä FI',
				'_ptv_charge_type_additional_info'     => 'Maksullisuuden lisätieto',
				'_ptv_languages'                       =>
					array(
						0 => 'et',
						1 => 'fi',
						2 => 'fr',
					),
				'taxonomies'                           =>
					array(
						'ptv-service-classes'    =>
							array(
								array(
									'name'       => 'Eläkkeet',
									'uri'        => 'http://urn.fi/URN:NBN:fi:au:ptvl:v1120',
									'parent_uri' => '',
								),
							),
						'ptv-ontology-terms'     =>
							array(
								array(
									'name'       => 'palvelut',
									'uri'        => 'http://www.yso.fi/onto/koko/p36438',
									'parent_uri' => 'http://www.yso.fi/onto/koko/p34717;http://www.yso.fi/onto/koko/p34032',
								),
							),
						'ptv-target-groups'      =>
							array(
								array(
									'name'       => 'Viranomaiset',
									'uri'        => 'http://urn.fi/URN:NBN:fi:au:ptvl:v2017',
									'parent_uri' => '',
								),
							),
						'ptv-life-events'        =>
							array(
								array(
									'name'       => 'Eläkkeelle siirtyminen',
									'uri'        => 'http://urn.fi/URN:NBN:fi:au:ptvl:v3003',
									'parent_uri' => '',
								),
							),
						'ptv-industrial-classes' =>
							array(
								array(
									'name'       => 'Ääni-, kuva- ja atk-tallenteiden tuotanto',
									'uri'        => 'http://www.stat.fi/meta/luokitukset/toimiala/001-2008/18200',
									'parent_uri' => 'http://www.stat.fi/meta/luokitukset/toimiala/001-2008/1820',
								),
							),
					),
				'_ptv_legislation'                     =>
					array(
						array(
							'name'     => 'Lakitiedot nimi FI',
							'web_page' => 'https://example.com/law',
						),
					),
				'_ptv_requirements'                    => 'Ehdot ja kriteerit FI',
				'_ptv_service_channels'                =>
					array(
						array(
							'service_channel_id' => '296d5160-8f9e-4357-a334-ba6374904819',
						),
					),
				'_ptv_organizations'                   =>
					array(
						array(
							'provision_type'  => '',
							'role_type'       => 'Responsible',
							'organization_id' => '5e7b2744-0bf5-4548-b8f3-af41397965e8',
						),
						array(
							'provision_type'         => 'Other',
							'role_type'              => 'Producer',
							'additional_information' => '',
							'organization_id'        => '5e7b2744-0bf5-4548-b8f3-af41397965e8',
						),
					),
				'_ptv_service_vouchers_in_use'         => 1,
				'_ptv_publishing_status'               => 'Published',
				'_ptv_modified'                        => '31.01.2018 14:19',
			);

		$data = unserialize( 'O:11:"PTV_Service":1:{s:12:" * container";a:26:{s:2:"id";s:36:"57f274dc-d837-4791-8ba0-3c845af1bf4b";s:9:"source_id";s:4:"1343";s:40:"statutory_service_general_description_id";N;s:4:"type";s:23:"PermissionAndObligation";s:12:"funding_type";s:12:"MarketFunded";s:13:"service_names";a:2:{i:0;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"sv";s:5:"value";s:15:"Testipalvelu SV";s:4:"type";s:4:"Name";}}i:1;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"fi";s:5:"value";s:15:"Testipalvelu FI";s:4:"type";s:4:"Name";}}}s:19:"service_charge_type";s:7:"Charged";s:9:"area_type";s:8:"AreaType";s:5:"areas";a:1:{i:0;O:8:"PTV_Area":1:{s:12:" * container";a:4:{s:4:"type";s:12:"Municipality";s:4:"code";N;s:4:"name";N;s:14:"municipalities";a:2:{i:0;O:16:"PTV_Municipality":1:{s:12:" * container";a:2:{s:4:"code";s:3:"018";s:4:"name";a:2:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:6:"Askola";s:8:"language";s:2:"fi";}}i:1;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:6:"Askola";s:8:"language";s:2:"sv";}}}}}i:1;O:16:"PTV_Municipality":1:{s:12:" * container";a:2:{s:4:"code";s:3:"010";s:4:"name";a:2:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:6:"Alavus";s:8:"language";s:2:"fi";}}i:1;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:6:"Alavus";s:8:"language";s:2:"sv";}}}}}}}}}s:20:"service_descriptions";a:10:{i:0;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"sv";s:5:"value";s:15:"Määräaika SV";s:4:"type";s:22:"DeadLineAdditionalInfo";}}i:1;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"fi";s:5:"value";s:15:"Määräaika FI";s:4:"type";s:28:"ProcessingTimeAdditionalInfo";}}i:2;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"fi";s:5:"value";s:17:"Toimintaohjeet FI";s:4:"type";s:22:"ServiceUserInstruction";}}i:3;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"fi";s:5:"value";s:18:"Muokattu kuvaus FI";s:4:"type";s:11:"Description";}}i:4;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"fi";s:5:"value";s:15:"Tiivistelmä FI";s:4:"type";s:16:"ShortDescription";}}i:5;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"sv";s:5:"value";s:9:"Kuvaus SV";s:4:"type";s:11:"Description";}}i:6;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"sv";s:5:"value";s:15:"Tiivistelmä SV";s:4:"type";s:16:"ShortDescription";}}i:7;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"fi";s:5:"value";s:25:"Maksullisuuden lisätieto";s:4:"type";s:24:"ChargeTypeAdditionalInfo";}}i:8;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"sv";s:5:"value";s:17:"Toimintaohjeet SV";s:4:"type";s:22:"ServiceUserInstruction";}}i:9;O:23:"PTV_Localized_List_Item":1:{s:12:" * container";a:3:{s:8:"language";s:2:"sv";s:5:"value";s:28:"Maksullisuuden lisätieto SV";s:4:"type";s:24:"ChargeTypeAdditionalInfo";}}}s:9:"languages";a:3:{i:0;s:2:"et";i:1;s:2:"fi";i:2;s:2:"fr";}s:15:"service_classes";a:1:{i:0;O:31:"PTV_Finto_Item_With_Description":1:{s:12:" * container";a:7:{s:4:"name";a:3:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:9:"Eläkkeet";s:8:"language";s:2:"fi";}}i:1;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:9:"Pensioner";s:8:"language";s:2:"sv";}}i:2;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:8:"Pensions";s:8:"language";s:2:"en";}}}s:11:"description";a:3:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:252:"I denna servicegrupp behandlas ur ett serviceperspektiv typer av lagstadgade pensioner i Finland samt pensionsskyddet för personer som är permanent bosatta i utlandet med tanke på både finländare bosatta utomlands och invandrare bosatta i Finland.";s:8:"language";s:2:"sv";}}i:1;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:222:"Tässä palveluluokassa käsitellään palvelunäkökulmasta Suomen lakisääteisten eläkkeiden tyyppejä sekä ulkomailla pysyvästi asuvan eläketurvaa niin ulkosuomalaisen kuin Suomessa asuvan maahanmuuttajan kannalta.";s:8:"language";s:2:"fi";}}i:2;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:238:"This service class contains the Finnish statutory pension types and the pension security of those permanently resident abroad from the perspective of both expatriate Finns and immigrants residing in Finland from the service point of view.";s:8:"language";s:2:"en";}}}s:4:"code";s:3:"P13";s:13:"ontology_type";s:4:"PTVL";s:3:"uri";s:38:"http://urn.fi/URN:NBN:fi:au:ptvl:v1120";s:9:"parent_id";N;s:10:"parent_uri";s:0:"";}}}s:14:"ontology_terms";a:1:{i:0;O:14:"PTV_Finto_Item":1:{s:12:" * container";a:6:{s:4:"name";a:3:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:7:"service";s:8:"language";s:2:"sv";}}i:1;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:8:"palvelut";s:8:"language";s:2:"fi";}}i:2;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:8:"services";s:8:"language";s:2:"en";}}}s:4:"code";s:0:"";s:13:"ontology_type";s:3:"YSO";s:3:"uri";s:34:"http://www.yso.fi/onto/koko/p36438";s:9:"parent_id";N;s:10:"parent_uri";s:69:"http://www.yso.fi/onto/koko/p34717;http://www.yso.fi/onto/koko/p34032";}}}s:13:"target_groups";a:1:{i:0;O:14:"PTV_Finto_Item":1:{s:12:" * container";a:6:{s:4:"name";a:3:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:12:"Viranomaiset";s:8:"language";s:2:"fi";}}i:1;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:11:"Authorities";s:8:"language";s:2:"en";}}i:2;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:11:"Myndigheter";s:8:"language";s:2:"sv";}}}s:4:"code";s:3:"KR3";s:13:"ontology_type";s:11:"TARGETGROUP";s:3:"uri";s:38:"http://urn.fi/URN:NBN:fi:au:ptvl:v2017";s:9:"parent_id";N;s:10:"parent_uri";s:0:"";}}}s:11:"life_events";a:1:{i:0;O:14:"PTV_Finto_Item":1:{s:12:" * container";a:6:{s:4:"name";a:3:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:17:"Att gå i pension";s:8:"language";s:2:"sv";}}i:1;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:23:"Eläkkeelle siirtyminen";s:8:"language";s:2:"fi";}}i:2;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:10:"Retirement";s:8:"language";s:2:"en";}}}s:4:"code";s:4:"KE10";s:13:"ontology_type";s:13:"LIFESITUATION";s:3:"uri";s:38:"http://urn.fi/URN:NBN:fi:au:ptvl:v3003";s:9:"parent_id";N;s:10:"parent_uri";s:0:"";}}}s:18:"industrial_classes";a:1:{i:0;O:14:"PTV_Finto_Item":1:{s:12:" * container";a:6:{s:4:"name";a:3:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:43:"Ääni-, kuva- ja atk-tallenteiden tuotanto";s:8:"language";s:2:"fi";}}i:1;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:30:"Reproduction of recorded media";s:8:"language";s:2:"en";}}i:2;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:28:"Reproduktion av inspelningar";s:8:"language";s:2:"sv";}}}s:4:"code";s:1:"5";s:13:"ontology_type";N;s:3:"uri";s:59:"http://www.stat.fi/meta/luokitukset/toimiala/001-2008/18200";s:9:"parent_id";s:36:"aa7a8e1f-63aa-44f0-a22c-5a5dc1f387ac";s:10:"parent_uri";s:58:"http://www.stat.fi/meta/luokitukset/toimiala/001-2008/1820";}}}s:11:"legislation";a:1:{i:0;O:7:"PTV_Law":1:{s:12:" * container";a:2:{s:5:"names";a:1:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:18:"Lakitiedot nimi FI";s:8:"language";s:2:"fi";}}}s:9:"web_pages";a:1:{i:0;O:12:"PTV_Web_Page":1:{s:12:" * container";a:3:{s:5:"value";N;s:3:"url";s:23:"https://example.com/law";s:8:"language";s:2:"fi";}}}}}}s:8:"keywords";a:0:{}s:12:"requirements";a:2:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:21:"Ehdot ja kriteerit FI";s:8:"language";s:2:"fi";}}i:1;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:21:"Ehdot ja kriteerit SV";s:8:"language";s:2:"sv";}}}s:16:"service_channels";a:1:{i:0;O:27:"PTV_Service_Service_Channel":1:{s:12:" * container";a:4:{s:15:"service_channel";O:8:"PTV_Item":1:{s:12:" * container";a:2:{s:2:"id";s:36:"296d5160-8f9e-4357-a334-ba6374904819";s:4:"name";s:12:"Palvelupiste";}}s:19:"service_charge_type";N;s:11:"description";a:0:{}s:22:"digital_authorizations";a:0:{}}}}s:13:"organizations";a:2:{i:0;O:24:"PTV_Service_Organization":1:{s:12:" * container";a:4:{s:14:"provision_type";N;s:12:"organization";O:8:"PTV_Item":1:{s:12:" * container";a:2:{s:2:"id";s:36:"5e7b2744-0bf5-4548-b8f3-af41397965e8";s:4:"name";s:15:"Valu Digital Oy";}}s:9:"role_type";s:11:"Responsible";s:22:"additional_information";a:0:{}}}i:1;O:24:"PTV_Service_Organization":1:{s:12:" * container";a:4:{s:14:"provision_type";s:5:"Other";s:12:"organization";O:8:"PTV_Item":1:{s:12:" * container";a:2:{s:2:"id";s:36:"5e7b2744-0bf5-4548-b8f3-af41397965e8";s:4:"name";s:15:"Valu Digital Oy";}}s:9:"role_type";s:8:"Producer";s:22:"additional_information";a:1:{i:0;O:17:"PTV_Language_Item":1:{s:12:" * container";a:2:{s:5:"value";s:24:"Tuottajan lisätiedot sv";s:8:"language";s:2:"sv";}}}}}}s:23:"service_vouchers_in_use";b:1;s:16:"service_vouchers";a:1:{i:0;O:19:"PTV_Service_Voucher":1:{s:12:" * container";a:5:{s:12:"order_number";i:0;s:5:"value";s:23:"Palvelusetelipalvelu SV";s:8:"language";s:2:"sv";s:3:"url";s:38:"http://www.example.com/servicevouchers";s:22:"additional_information";s:36:"Palvelusetelipalvelun lisätiedot SV";}}}s:19:"service_collections";a:0:{}s:17:"publishing_status";s:9:"Published";s:8:"modified";O:8:"DateTime":3:{s:4:"date";s:26:"2018-01-31 14:19:07.849286";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}}}' );

		$got = $post_type_helper->serialize( $data, 'fi' );

		// Replace this with some actual testing code.
		$this->assertEquals( $expected, $got );
	}

}
