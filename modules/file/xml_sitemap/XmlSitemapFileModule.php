<?php
class XmlSitemapFileModule extends FileModule {

	private $sXmlNamespace = 'http://www.sitemaps.org/schemas/sitemap/0.9';
	private $oXmlDocument;
	private $oUrl;
	
	public function __construct($aRequestPath) {
		parent::__construct($aRequestPath);
	}
	
	private function element($sName, $sContent) {
		$oUrl = $this->oXmlDocument->createElement($sName);
		$oUrl->appendChild($this->oXmlDocument->createTextNode($sContent));
		return $oUrl;
	}
	
	private function renderNavigationItem($oNavigationItem) {
		$oUrl = $this->oXmlDocument->createElement('url');
		
		//Location, absolute url
		$sAbsoluteLink = LinkUtil::absoluteLink(LinkUtil::link($oNavigationItem->getLink(), 'FrontendManager'), null, LinkUtil::isSSL());
		$oUrl->appendChild($this->element('loc', $sAbsoluteLink));

		//Last modified
		if($oNavigationItem instanceof PageNavigationItem) {
			$oUrl->appendChild($this->element('lastmod', $oNavigationItem->getMe()->getUpdatedAt('Y-m-d')));
		} else {
			//@todo: figure out how to retrieve last modified for content other then text and configuration: FrontendModules
		}
		
		//Priority
		$iPriority = 1;
		if($oNavigationItem->getCanonical() !== null) {
			$iPriority = 0.5;
		}
		$oUrl->appendChild($this->element('priority', $iPriority));
		
		$this->oUrl->appendChild($oUrl);
		
		foreach($oNavigationItem->getChildren(null, false, true) as $oChild) {
			$this->renderNavigationItem($oChild);
		}
	}
	
	public function renderFile() {
		$this->oXmlDocument = new DOMDocument();
		$this->oUrl = $this->oXmlDocument->createElement('urlset');
		$this->oUrl->setAttribute('xmlns', $this->sXmlNamespace);
		$this->oXmlDocument->appendChild($this->oUrl);
		
		$oRootPage = PagePeer::getRootPage();
		$oRootNavigationItem = PageNavigationItem::navigationItemForPage($oRootPage);
		
		$this->renderNavigationItem($oRootNavigationItem);

		header('Content-Type: application/xml;charset=utf-8');
		print $this->oXmlDocument->saveXML();
	}
}