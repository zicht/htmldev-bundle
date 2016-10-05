# Twig function: `classes`

Function that conditionally builds a CSS class name. 

When building a component (or any piece of HTML, really) in Twig, it's quite common to render or not render based on cerrtain variables. If you manually contatenate the class names, it quickly becomes a mess of `if` / `else` or ternay operators. This function helps to keep that part readable.

## Usage

```
{{ classes('art-vandelay') }} ⇒ 'art-vandelay'
{{ classes(['art-vandelay', 'kramerica']) }} ⇒ 'art-vandelay  kramerica'
{{ classes(['art-vandelay' => true, 'kramerica' => false]) }} ⇒ 'art-vandelay'
{{ classes('art-vandelay', ['kramerica' => false, 'kel-varnsen' => true]) }} ⇒ 'art-vandelay  kel-varnsen'
```

## Example

When classes are manually added by checking one ore more properties, code can be quite hard to read:

```
{% set classes = [
    image.url ? 'c-card--with-image' : 'c-card--no-image  u-text--center',
    equal_height  ? 'c-card--equal-height@sm' : ''
] %}
   
<artlcie class="{{ classes|join('  ') }}">
    ...
</article>
```

With the `classes` function, it becomes much more apparent which class is toggled by what property:

```
{% set classes = classes([
    'c-card--with-image': image.url,
    'c-card--no-image  u-text--center': not image.url,
    'c-card--equal-height@sm': equal_height
]) %} 
   
<artlcie class="{{ classes }}">
    ...
</article>
```