# shortcode-hunter
The Shortcode Hunter - PHP shortcode class, nested shortcodes and custom shortcodes

### Installation

```ruby
composer require enesakarsu/shortcode-hunter
```

### Syntax of shortcodes

Shortcodes are written in square brackets.Every opened shortcodes should be closed. Usage example;

```ruby
[example]shortcode[/example] and example text
```

You can send parameters to the shortcodes if you want. Parameters are written in two curly braces. Usage example;

```ruby
[post id={{22}}][/post]
and
[text color={{red}}]colored text[/text]
```

### Why do shortcodes use?

You can capture shortcodes in a text and make them do whatever you want.

Example:
`[post id={{1}}][/post]`

We can capture this shortcode in the text and pull the content with an id value of 1 from the database.

Example:
`[date][/date]`

We can create a shortcode named "date" and print the current time in text.

What you (guys)can do is totally up to your imagination.




Usage Example
=============

### Creating a shortcode

To create a shortcode, open the `shortcode-hunter/src/shortcodes.php` file and create the shortcode you want like the example below. While creating the shortcode, we write the name of our shortcode as the first parameter and the name of the function we want to run as the second parameter.

Normal function:

```ruby
$shortcode->create("example", "exampleFunction");
```

Class method:

```ruby
$shortcode->create("example", "class@method");
```


Above, we asked it to run the function named "exampleFunction" for the "example" shortcode. Now let's create that function in the `shortcode-hunter/src/functions.php` file.

---
**NOTE**

The first parameter of the function to be created should be $values ​​and the second parameter should be $content. Otherwise, parameters and content cannot be accessed.

---


```ruby
function exampleFunction($values, $content){
return "hello";
}
```

### Parsing the shortcode

Create an index.php file and include the shortcode-hunter class in the file. Then send some text as a parameter to the shortcode's unparse method.


```ruby
<?php
include "shortcode-hunter/src/hunter.php";

$text = "Some text and [example]shortcode[/example]";

echo $shortcode->parse($text);

?>
```

If I run the index.php file, the output I will get is:

Output:
`hello`

### Accessing the content of the shortcode

If I want to access the content of the shortcode;

```ruby
function exampleFunction($values, $content){
return $content;
}
```
Output:
`shortcode`

### Getting parameters sent to shortcode


`[example id={{22}}]shortcode[/example]`
If we are sending parameters to the shortcode, we can capture them like this;

```ruby
function exampleFunction($values, $content){
return $values["id"];
}
```

Output:
`22`


---
**NOTE**

If you echo instead of return in the function that the shortcode will run, it will appear at the very beginning of the text. To avoid this you need to do output buffering.

```ruby
ob_start();

//your codes
//your codes

$output = ob_get_contents();
ob_end_clean();

return $output;
```


Example;

```ruby
function exampleFunction($values, $content){

ob_start();

$fruits = ["apple", "banana", "strawberry"];

foreach($fruits as $fruit){
echo $fruit;
}

$output = ob_get_contents();
ob_end_clean();

return $output;

}

```

---




