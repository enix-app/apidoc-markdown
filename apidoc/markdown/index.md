{# !!! DO NOT USE INDENTATION !!! MARKDOWN HERE !!! #}

{% block content %}

# References

## Class

{% for package, classes in refs.classes %}
### {{ package }}
{% for name, path in classes %}
{{ loop.index }}. [{{ name }}]({{ apidoc.path(name) }})
{% endfor %}

{% endfor %}

## Helper

{% for package, groups in refs.helpers %}
### {{ package }}
{% for group, helpers in groups %}
#### {{ group }}
{% for func, name in helpers %}
{{ loop.index }}. [{{ func }}]({{ apidoc.path(name) ~ '#' ~ func }})
{% endfor %}

{% endfor %}

{% endfor %}

{# ================== #
```php
{{ dump(refs)|raw }}
```
 # ================== #}

<hr>
{% endblock %}
