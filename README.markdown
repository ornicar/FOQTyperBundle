Disclaimer: this bundle is about typing less. Your keyboard will thank you.

## Assumptions

1. Symfony2 is the glorification of Object Oriented Programming.
A popular pattern, called [Single responsibility](http://en.wikipedia.org/wiki/Single_responsibility_principle),
states that each class should have only one purpose.

2. It also means that for every single purpose you must write a new class.

3. If you follow this pattern (you should!) you will end up with lots of classes.

4. Classes are verbose. They have a namespace, uses, extends, implements, properties, constructors, getters and setters.
Oh, all these things have phpDoc and comments.

5. You are lazy and your fingers are tired.

## Installation

### Add TyperBundle to your src/ dir

    $ git submodule add git://github.com/ornicar/TyperBundle.git src/FOQ/TyperBundle

### Add the FOQ namespace to your autoloader

    // app/autoload.php

    $loader->registerNamespaces(array(
        'FOQ' => __DIR__.'/../src',
        // your other namespaces
    );

### Add TyperBundle to your application kernel

    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            new FOQ\TyperBundle\FOQTyperBundle(),
            // ...
        );
    }

## Code generation

TyperBundle works by converting a YAML template to PHP code.

### 1. Get a template

First, create a php file containing the yaml template:

    $ app/console typer:template > src/Vendor/CookBundle/Toaster.php

src/Vendor/CookBundle/Toaster.php now contains

    name:
    comment:
    extends:
    implements: []
    properties:
        foo:
            type:
            visibility: protected
            comment:
            default: null
            getter: true
            setter: true
            construct: true

### 2. Fill the YAML template

Complete the template as if it was a form.
Only `name` is required, you can remove the rest if you don't need it.

Example of simple template:

    name: Vendor\CookBundle\Toaster
    comment: Takes a donnut and returns it toasted

Example of more complete template:

    name: Vendor\CookBundle\Toaster
    comment: Takes a donnut and returns it toasted
    extends: Vendor\MachineBundle\Electrical
    implements: [ Vendor\CookBundle\ToasterInterface ]
    properties:
        duration:
            type: int
            visibility: private
            comment: Toasting duration in seconds
            default: 20
            getter: true
            setter: true
            construct: true
        timer:
            type: Vendor\MachineBundle\Timer
            comment: Timer used to measure the toasting duration
            construct: true

### 3. Generate the PHP code

    $ app/console typer:generate src/Vendor/CookBundle/Toaster.php

It will read the YAML configuration from the file, and replace it with PHP code.

The first example of simple template will result to:

    <?php

    namespace Vendor\CookBundle;

    /**
    * Takes a donnut and returns it toasted
    */
    class Toaster
    {

    }

The second example of complete template will result to:

    <?php

    namespace Vendor\CookBundle;

    use Vendor\MachineBundle\Electrical;
    use Vendor\MachineBundle\Timer;

    /**
    * Takes a donnut and returns it toasted
    */
    class Toaster
    {

        /**
        * Toasting duration in seconds
        * 
        * @var int
        */
        private $duration = 20;

        /**
        * Timer used to measure the toasting duration
        * 
        * @var Timer
        */
        protected $timer = null;

        /**
        * Instanciates a new Toaster
        * 
        * @param int duration
        * @param Timer timer
        */
        public function __construct(int $duration, Timer $timer)
        {
            $this->duration = $duration;
            $this->timer = $timer;
        }

        /**
        * Gets: Toasting duration in seconds
        * 
        * @return int duration
        */
        public function getDuration()
        {
            return $this->$duration;
        }

        /**
        * Sets: Toasting duration in seconds
        * 
        * @param int duration
        */
        public function setDuration(int $duration)
        {
            $this->duration = $duration;
        }
    }

## Custom templates

`app/console typer:template` gives you a default template.
You are encouraged to create yours, that match your projects needs.
Save the template anywhere, then load it to your file!
