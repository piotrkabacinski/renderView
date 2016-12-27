# Viewer

renderView static class is a little helper for bringing MVC feeling into small projects. Gather the data and insert it into the specific template.

## Usage

renderView class constructor requires two parameters: 
* path to view's template 
* content array

```php
\view\renderView::render( "views/template.html" , array( "name" => "world" ) );
```

Variables in template are expressed within `{{}}` brackets. Their content indicates specific element in array:

```html
<h1> Hello {{name}}! </h1>
```

```php
$array = array( "name" => "World" );
```
This example will return:

```html
<h1> Hello World! </h1>
```
For listings or repeating elements you can use loop: `{{loop:LoopName}} ... {{/loop:LoopName}}`.

```html
<ul>
		{{loop:names}}
			<li>{{name}} , age: {{age}}</li>
		{{/loop:names}}
</ul>
```
```php
$array = array( 
          "names" => array(
          		array( "name" => "John" , "age" => 25 ),
          		array( "name" => "Billy" , "age" => 20 ),
          		array( "name" => "Alice" , "age" => 19 )
          )
);
```
Be aware that loops within loops can cause some problems for now. Keep it in mind while programming your model.

## Licence

MIT
