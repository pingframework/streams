# Ping - Streams

**Ping Streams** is simple port of Java8 Streams API to PHP. 
The [FluentTraversable](https://github.com/psliwa/fluent-traversable) library was taken as a base for this project,
redesigned and reworked to PHP 8.1.

**Ping Streams** is small tool that adds a bit of **functional programming** to php, especially for arrays
and collections. This library is inspired by java8 stream framework, guava FluentIterable and Scala functional features.

# Quick example

*We have an array of patients and we want to know percentage of female patients grouped by blood type.*

```php

    $patients = [...];
    
    $info = Stream::of($patients)
        ->groupBy(get::value('bloodType'))
        ->map(
            StreamPipeline::forIterable()
                ->partition(is::eq('sex', 'female'))
                ->map(func::unary('count'))
                ->collect(function($elements){
                    [$femalesCount, $malesCount] = $elements;
                    return $femalesCount / ($femalesCount + $malesCount) * 100;
                })
        )
        ->toMap();
```

Explanation and more information about this example you get [here](#example2).

# ToC

1. [Installation](#installation)
1. [Stream](#steam)
1. [StreamPipeline](#pipeline)
    1. [Stream as predicate / mapping function](#pipelineAsPredicate)
1. [Predicates](#predicates)
1. [Puppet](#puppet)
1. [Contribution](#contri)
1. [License](#license)

<a name="installation"></a>
## Installation

Installation is very easy (thanks to composer ;)):

*(add to require section in your composer.json file)*

``
"pingframework/streams": "*"
``

You should choose last stable version, wildcard char ("*") is only an example.

<a name="stream"></a>
## Stream

With the `Stream` class you can operate on arrays and collection in declarative and readable way. There is a
simple example.

*We want to get emails of male authors of books that have been released before 2007.*

```php

    $books = [/* some books */];
    
    $emails = [];
    
    foreach($books as $book) {
        if($book->getReleaseDate() < 2007) {
            $authors = $book->getAuthors();
            foreach($authors as $author) {
                if($author->getSex() == 'male' && $author->getEmail()) {
                    $emails[] = $author->getEmail();
                }
            }
        }
    }
```

Ok, nested loops, nested if statements... It doesn't look good. If I use php array_map and array_filter functions, result
wouldn't be better, would be even worst, so I omit this example.

<a name="example1"></a>
The same code using `Stream`:

```php

    $books = [/* some books */];
    
    $emails = Stream::of($books)
        ->filter(is::lt('releaseDate', 2007))
        ->flatMap(get::value('authors'))
        ->filter(is::eq('sex', 'male'))
        ->map(get::value('email'))
        ->filter(is::notNull())
        ->toList();       

```

> **IMPORTANT**
>
> In examples `toMap` and `toList` functions are used to convert elements to array. The difference between those
> two functions is `toList` **re-indexes elements**, `toMap` **preserves indexes**. You should use `toList` method
> when indexes in your use case are not important, otherwise you should use `toMap`.

There are no loops, if statements, it looks straightforward, flow is clear and explicit (when you now what `filter`,
`flatMap`, `map` etc methods are doing - as I said before the basics functional programming patters are needed ;)).

> **IMPORTANT**
>
> What does `flatMap` do? It maps single values to collections of values and then merges all those collections into
> one collection. In example above `Book` has many authors, thanks to `flatMap` we are able to extract all authors to
> one dimensional array. When we would use `map`, on output would be array of authors' arrays.

`is` class (alias to `Predicates` class) is factory for closures that have one argument and evaluate it to boolean
value. There are `lt`, `gt`, `eq`, `not` etc methods. Closures in php are very lengthy, you have to write `function`
keyword, curly braces, return statement, semicolon etc - a lot of syntax noise. To handle simple predicate cases, you
might use `is` class. More about predicates you can read in [Predicates](#predicates) section.

<a name="get-value"></a>
`get::value('authors')` also is a shortcut for closures, this is semantic equivalent to:

```php

    // short syntax (arrow function)
    fn(Book $book): array => $book->getAuthors();
    
    // or full syntax
    function(Book $book): array {
        // some more code...
        return $book->getAuthors();
    }

```

Nested paths in predicates and `get::value` function are supported, so this code works as expected:
`get::value('address.city.name')`.

> **IMPORTANT**
>
> In the most of functions (where make it sense) to predicate/mapping function are provided two arguments: element value
> and index:
>
> ```php
>     Stream::of(['a' => 'A', 'b' => 'B'])
>         ->map(fn(string $value, string $index): string => $value.$index)
>         ->toMap();
>         // result will be: ['a' => 'Aa', 'b' => 'Bb']
> ```
>
> When you won't index to be passed as second argument, you could use `func::unary($func)` function. It is very helpful
> especially when you want to use php build-in function that has optional second argument with different meaning, for
> example `str_split`:
>
> ```php
>     Stream::of(['some', 'values'])
>         ->flatMap(func::unary('str_split'))
>         ->toList();
>         // result will be: ['s', 'o', 'm', 'e', 'v', 'a', 'l', 'u', 'e']
> ```

`Stream` has a lot of useful methods: `map`, `flatMap`, `filter`, `unique`, `groupBy`, `sort`, `allMatch`,
`anyMatch`, `noneMatch`, `firstMatch`, `maxBy`, `minBy`, `reduce`, `toList`, `toMap` and more. List, description and examples
of all those methods are available in StreamInterface. Each method belongs to one of two groups:
intermediate or terminate operations. Intermediate operation does some work on input array, modifies it and returns
`Stream` (self/static) object for further processing, so you can chain another operation. Terminate operation does some
calculation on each element of array and returns result of this calculation. For example `size` operation returns
integer that is length of input array, so you can not chain operation anymore.

Example:

```php

    Stream::of([])
        ->filter(...)//intermediate operation, so I can chain
        ->map(...)//intermediate operation, so I can chain
        ->size()//terminate operation, I cannot chain - it returns integer

```
<a name="option"></a>
There are few terminal operations that returns `Option` value (if you don't know what is Option or Optional value pattern,
follow this links: [php-option][2], [Optional explanation in Java][1]). For example `firstMatch` method could find nothing,
so instead return null or adding second optional argument to provide default value, `Option` object is returned. `Option`
object is a wrapper for value, it can contain value, but it haven't to. You should threat `Option` as collection with 0 or 1
value. `Option` class provides few familiar methods to `Stream`, for example `map` and `filter`. You can get
value from `Option` by `getOrElse` method:

Example:

```php

    Stream::of($books)
        ->firstMatch(is::eq('author.name', 'Stephen King'))
        // there is Option instance, you can transform value (thanks to map) if this value exists
        ->map(fn(Book $book): string => 'Found book: '.$book->getTitle())
        // provide default value if book wasn't found
        ->orElse(Option::fromValue('Not found any book...'))
        // print result to stdout thanks to Option::map method
        ->map('printf');
        // or you can call "->get()" and assign to variable, it is safe because you provided default value by "orElse"

```

If Stephen King's book was found, "Found book: TITLE" will be printed, otherwise "Not found any book...".

Properly used, option is very powerful and it integrates with `Stream` perfectly. `Option::map` method is
very inconspicuous, but it is also very useful. Thanks to `Option::map` you can execute piece of code when value is
available without using `if` statement:

```php

    Stream::of($books)
        ->maxBy(get::value('rating'))
        ->map(fn(Book $book): void => $this->takeToBackpack($book));

```

> **IMPORTANT**
>
> `Option` in many cases is very useful and it often simplifies the code.
> `Option` has `getOrElse` method, so you can eventually use it to grab the value or default value. However I recommend
> you to learn how to properly use this pattern, in literature it is also called `Maybe` or `Optional` pattern.


> **IMPORTANT**
>
> When you want to use `Option::map` function, be aware when provided mapping function returns `null`, `map` function will
> return `Some(null)` (not `None()`) - that could be undesirable. Example below is not correct if `$patientRepo::find()`
> method might return `null`:
>
> ```php
> Option::fromValue($patientId)
>   ->map([$patientRepo,'find'])
>   //there could be `Some(null)` value! 
>   ->map(get::value('doctor.phone'))
>   //there could be also `Some(null)` value, so `null` might be passed to `$this::callToDoctor`
>   ->each([$this,'callToDoctor']);
> ```
>
> When you want to transform value wrapped by `Option` and mapping function could return `null` you should use `flatMap`
> and `get::option()` combo. There is correct example:
>
> ```php
> Option::fromArrayValue($patientId)
>   ->flatMap(get::option([$patientRepo,'find']))
>   //when `$this::callToDoctor` return `null` there will be `None`
>   ->flatMap(get::option('doctor.phone'))
>   //when doctor has not phone set, there will be `None` value
>   ->each([$this,'callToDoctor']);
> ```
>
> `get::option` is similar to `get::value`, the difference is it wraps value in `Option` type.

<a name="pipeline"></a>
## StreamPipeline

`StreamPipeline` is a tool to compose complex operations on arrays. You can define one complex operation thanks to
pipeline, and apply it multiple times on any array.

There is an example:

```php

    $maxEvenPrinter = StreamPipeline::forIterable();

    // very important is, to not chain directly from `forArray()` method, first you should assign created object
    // to variable, and then using reference to object you can compose your function

    $maxEvenPrinter
        ->filter(fn(int $number): bool => $number % 2 === 0) 
        // only even numbers
        ->max()
        // "max" (as same as firstMatch) returns Option, because there is possibility given array is empty
        ->map(fn(int $value): string => 'max even number: '.$value)
        ->orElse(Option::fromValue('max even number not found'))
        ->map('printf');

```

Ok, we have `$maxEvenPrinter` object, what's next?

```php

    $maxEvenPrinter([1, 3, 5, 2, 4]);
    // output will be: "max even number: 4"
    
    $maxEvenPrinter([1, 3, 5]);
    // output will be: "max even number not found"

```

As I said, `StreamPipeline` has almost the same methods as `Stream` object. The difference between those two classes
is that, `Stream` needs input array when object is created and it should be used once, `StreamPipeline`
doesn't need array when object is created and can be invoked multiple times with different input arrays. Internally
`StreamPipeline` uses `Stream` instance ;) You should threat `StreamPipeline` as tool to compose functions.

**`StreamPipeline` has three factory methods that differ in arguments that are accepted by created function:**

* `StreamPipeline::forIterable()` - created function accepts one array/traversable argument

    ```php

        $func = StreamPipeline::forIterable();
        $func-> /* some chaining methods */;
            
        $func(['value1', 'value2', 'value3']);

    ```

* `StreamPipeline::forVarargs()` - created function accepts variable number of arguments (varargs):

    ```php
    
        $func = StreamPipeline::forVarargs();
        $func-> /* some chaining methods */;
        
        $func('value1', 'value2', 'value3');
    
    ```

* `StreamPipeline::forValue()` - created function accepts one argument that will be threaten as only element of array.
  This method is similar to `StreamPipeline::forVarargs()`, the difference is all arguments are ignored except the first.

    ```php
    
        $func = StreamPipeline::forValue();
        $func-> /* some chaining methods */;
    
        $func('value1', 'this value will be ignored')
    
    ```

<a name="pipelineAsPredicate"></a>
### StreamPipeline as predicate / mapping function

You can use `StreamPipeline` to create predicate or mapping function for `Stream`, especially after
functions that transforms single value to array of values (`groupBy`, `partition` etc.).

Example:

<a name="example2"></a>
*We have an array of patients and we want to know percentage of female patients grouped by blood type.*

```php

    $patients = [...];
    
    $info = StreamPipeline::of($patients)
        ->groupBy(get::value('bloodType'))
        // we have multi-dimensional array, where key is bloodType, value is array of patients
        ->map(
            // we map array of patients for each blood type to percentage value, so lets compose a function
            StreamPipeline::forIterable()
                // split array of patients into two arrays, first females, second males
                ->partition(is::eq('sex', 'female'))
                // map those arrays to its size, so we have number of females and males
                ->map(func::unary('count'))
                // calculate a percent
                ->collect(function(array $elements): float{
                    [$femalesCount, $malesCount] = $elements;
                    return $femalesCount / ($femalesCount + $malesCount) * 100;
                })
        )
        // get our result with index preserving 
        ->toMap();
```

> **IMPORTANT**
>
> Directly chaining from `StreamPipeline::forIterable()` (and other factory methods) is not always safe, some methods does not
> return `StreamPipeline`, but `Option` object. Methods that returns `Option` are: `reduce`, `firstMatch`, `max`,
> `min`, `first`, `last`, `get`. When you after all want to chain directly from `StreamPipeline::forIterable()` and use terminal
> operation that returns `Option`, you can apply a trick:
>
> ```php
>
>   ->map(
>       $f = StreamPipeline::forIterable(), $f
>            ->firstMatch(is::eq('name', 'Stefan'))
>            ->getOrElse('Not found')
>   )
>
> ```

There is also `StreamPipeline::forValue()` method to create function with one argument that contains single value. It might be
useful to create predicates or mapping functions for single value.

Example:

<a name="example3"></a>
*We want to find doctors that all patients are women (gynecologists?).*

```php
    
    $doctors = [/* some doctors */];

    $doctors = Stream::of($doctors)
        ->filter(
            StreamPipeline::forValue()
                ->flatMap(get::value('patients'))
                ->allMatch(is::eq('sex', 'female'))
        )
        ->toList();

```

<a name="predicates"></a>
## Predicates

Predicate is a function that evaluates single value to boolean. Predefined predicates are available in `is` and `Predicates`
classes. Those classes are the same, `is` is an alias to `Predicates`, so you can choose witch one to use (`is` gives more
expressiveness to code). Predicates are perfect to use in `filter`, `firstMatch`, `partition`, `allMatch`, `noneMatch`,
`anyMatch` methods of `Stream`.

The most of predicates (for example: `eq`, `notEq`, `gt`, `qte`, `identical`, `notIdentical`, `in`, `notIn`, `contains`)
have two versions:

* unary: `predicate($valueToCompare)`

    ```php
    
        $gt25 = is::gt(25);    
        $gt25(26);//evaluates to true
    
    ```

* binary - `predicate($property, $valueToCompare)`

    ```php
    
        $ageGt25 = is::gt('age', 25);
        $gt25(['age', 26]); // evaluates to true
    
    ```

Few predicates (`null`, `notNull`, `false`, `true`, `blank`, `notBlank`) have also two, but different versions:

* not argument: `predicate()`

    ```php
    
        $true = is::true();    
        $true(true); // evaluates to true
    
    ```

* unary: `predicate($property)`

    ```php
    
        $true = is::true('awesome');    
        $true(['awesome' => true]); // evaluates to true
    
    ```

There are also logical predicates (`not`, `allTrue` - logical and, `anyTrue` - logical or), but when you need to create
complex predicate maybe the better and more readable way is just to use closure. `allTrue` and `anyTrue` accepts also
evaluated values, for example:

```php

    $alwaysFalse = is::allTrue(false, is::eq(25));
    $alwaysTrue = is::anyTrue(true, is::eq(25));

```

Evaluated values could be useful when filtering of some values depends on external condition and you don't want to use
separate `if` statement because of readability purpose - of course if your array is really big, be aware the iteration
through all elements will be done, so be carefully and use that feature consciously.

> **IMPORTANT**
>
> Predicates can also be used with **grouping functions**. Now there is only `size::of()` function.
>
> Example:
>
> *We want to find doctors with less than 5 patients*
>
> ```php
> 
>     $doctors = [...];
>     
>     $doctors = Stream::of($doctors)
>         ->filter(is::lt(size::of('patients'), 5))
>         ->toList();
> 
> ```

<a name="puppet"></a>
## Puppet

Puppet is a very small (less than 100 lines of code) class, but it is also very powerful. What is a Puppet? Thanks to
Puppet you can "record" some behaviour and execute this behaviour multiple times on various objects.

Example:

```php

    $book = [...];
    $puppet = Puppet::record()->getPublisher()->getName();
    
    echo $puppet($book); // $book->getPublisher()->getName() will be invoked

```

`Puppet` supports property access, array access and method calls with arguments. Originally it was created to simplify `map` and
`flatMap` operations in `Stream`. It is is also used internally by `StreamPipeline`, but maybe you will find
another use case for `Puppet`.

Puppet has two factory methods: `record` and `object` - those methods are the same, `object` method was created only for
semantic purpose. You can use `Puppet` to create mapping function for `map`, `flatMap` etc. functions, but `get::value()`
is recommended for this purpose.

`the` class is alias to `Puppet`, it only adds semantic meaning to using `Puppet` in `Stream` context:
`->map(the::object()->getName())` is much more readable than `->map(Puppet::record()->getName())`.

<a name="contri"></a>
## Contribution

Any suggestions, PR, bug reports etc. are welcome ;)

<a name="license"></a>
## License

**MIT** - details in [LICENSE](LICENSE) file

[1]: http://www.nurkiewicz.com/2013/08/optional-in-java-8-cheat-sheet.html
[2]: https://github.com/schmittjoh/php-option
