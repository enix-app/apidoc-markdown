{% import "markdown/macro.md" as md %}

{# !!! DO NOT USE INDENTATION !!! MARKDOWN HERE !!! #}

{# ========================= DEFINE MACRO ========================= #}

{% macro class_component(class_) %}

{{ md.class_info(class_) }}

{{ md.docs(class_.docs) }}

{% if class_.constants|length > 0 or class_.properties|length > 0 or class_.methods|length > 0 %}
### Summary

{{ md.summary(class_) }}
{% endif %}

{% if class_.constants %}

### Class Constants

{{ md.class_constants(class_.constants) }}

{% endif %}

{% if class_.properties %}

### Properties

{{ md.properties(class_.properties) }}

{% endif %}

{% if class_.methods %}

### Methods

{{ md.functions(class_.methods, true) }}

{% endif %} {# END CLASS METHODS #}

{% endmacro %} {# END class_component #}

{# ========================= STARTING CONTENT BLOCK ========================= #}

{% block content %}

{{ md.navigator(doc.path) }}

{% if doc.document.namespaces %}

{% for ns in doc.document.namespaces %}

{% if ns.name is not empty %}

# {{ ns.name }} {# NAMESPACE NAME #}

<table style="text-align:left">
<tr><th>namespace</th><td>{{ ns.name }}</td></tr>
</table>

{{ md.docs(ns.docs) }} {# NAMESPACE DESCRIPTION & TAGS #}

{{ md.siblings(ns.name) }}

{% endif %} {# endif ns.name #}

{% if ns.uses %} {# NAMESPACE USE & ALIAS #}
{{ md.uses(ns.uses) }}
{% endif %}

{# CLASS COMPONENTS #}
{% for class_ in ns.classes %} {# STARTING LOOP CLASS #}
{{ _self.class_component(class_) }}
{% endfor %}

{% if ns.functions %}

### Functions

{{ md.functions(ns.functions, true) }}
{% endif %} {# END FUNCTIONS #}

{% endfor %} {# endfor doc.document.namespaces #}
{% endif %} {# endif doc.document.namespaces #}

{# NO NAMESPACES #}

{% if doc.document.classes %}
{# CLASS COMPONENTS #}
{% if doc.document.uses %} {# NAMESPACE USE & ALIAS #}
{{ md.uses(doc.document.uses) }}
{% endif %}

{% for class_ in doc.document.classes %} {# STARTING LOOP CLASS #}
{{ _self.class_component(class_) }}
{% endfor %}
{% endif %}

{% if doc.document.functions %}

# {{ my.helperTitle(doc.pathinfo.filename) }}

{{ md.functions(doc.document.functions) }}

{% endif %}

{# ================== #
```php
{{ dump(doc)|raw }}
```
 # ================== #}

<hr>
{{ md.navigator(doc.path, true) }}

{% endblock %}
