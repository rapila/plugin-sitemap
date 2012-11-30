# Sitemap-Plugin
This plugin is a plugin that works seamlessly with Rapila CMS
It includes
* FileModule XMLSitemap 
	to generate of a XML document compatiple with http://www.sitemaps.org/protocol.html
* Html Sitemap Example 
	tips on how to implement a html sitemap in your Rapila site


## FileModule XMLSitemap
i.e. for search engines like Google, can be included in the robots.txt

Sitemap: http://www.yourdomain.com/get_file/xml_sitemap

## Html Sitemap Example
This example requires only a sitemap.tmpl and a navigation configuration specified for the sitemap
You can choose whether you want to 
* show hidden pages/navigation item
* hide duplicates of canonical pages
* the number of levels you want to display
* the html and css of the rendered items by defining your own inline templates

An example of a typical sitemap navigation

```yaml
navigations:
  sitemap:
    exclude_duplicates: true
    show_hidden: true
    0:
      - {template_inline: '<ul><li><a href="{{link}}">{{title}}</a><ul>{{children}}</ul></li></ul>'}
    all:
      - {template_inline: '<li class="level_{{level}}"><a class="{{name}}" href="{{link}}">{{title}}</a><ul>{{children}}</ul></li>'}
    4:
      - show: false
```

Example of how to configure the sitemap navigation in the template

```html
<body class="sitemap">
	<div>
		{{navigation=sitemap}}
	</div>
</body>
```