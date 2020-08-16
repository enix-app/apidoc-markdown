{# !!! DO NOT USE INDENTATION !!! MARKDOWN HERE !!! #}

{% macro rejoin(text) %}
{{ text|split("|")|join("<br>")|raw }}
{% endmacro %}

{# Get docs sub tags #}

{% macro description_tags(tag) %}
{% if tag.link %}
<a href="{{ tag.link }}">{{ tag.link }}</a>
{% endif %}{{ tag.authorName ?? '' }}{{ tag.description.body|raw }}
{% endmacro %}

{# Get docs tags #}

{% macro tags(tags) %}
<table style="text-align:left">
{% for tag in tags %}
<tr style="vertical-align:top;">
<th>{{ tag.name }}</th>
<td>{{ _self.description_tags(tag) }}</td>
</tr>
{% endfor %}
</table>
{% endmacro %}

{# Get document docs #}

{% macro description(docs) %}
<details>
<summary style="margin-bottom:12px;"><strong>Description</strong></summary>

{% if docs.description.short %}
<table>
<tr><td>
{{ docs.description.short|raw }}
</td></tr>
</table>
{% else %}
*No description.*
{% endif %}

{% if docs.description.long.body is not empty %}
<table>
<tr><td>
{{ docs.description.long.body|raw }}
</td></tr>
</table>
{% endif %}

</details>

{% endmacro %}

{% macro docs(docs) %}
{% if docs is not empty %}
{{ _self.description(docs) }}

{{ _self.tags(docs.tags) }}
{% endif %}
{% endmacro %}

{# Get parameter #}

{% macro params(params) %}
{% if params %}
**Params**

<table>
<thead>
<tr>
<th>#</th>
<th>Variable</th>
<th>Type</th>
<th>Variadic</th>
<th>Description</th>
</tr>
</thead>
<tbody>

{% for param in params %}
<tr>
<td>{{ loop.index }}.</td>
<td><code>${{ param.varName }}</code></td>
<td><em>{{ _self.rejoin(param.type) }}</em></td>
<td>{{ param.isVariadic ? 'true' : 'false' }}</td>
<td>{% if param.description.body %}{{ param.description.body|raw }}{% endif %}</td>
</tr>

{% endfor %}

</tbody>
</table>
{% else %}
{# NOT DOCUMENTED #}
{% endif %}
{% endmacro %}

{# Get source #}

{% macro source(attr) %}
{% if attr.source %}
{% set countLines = (attr.endLine - attr.startLine) + 1 %}

{% if countLines == 1 %}
{% set lineInfo = 'line ' ~ attr.startLine %}
{% else %}
{% set lineInfo = countLines ~ ' lines (' ~ attr.startLine ~ ' - ' ~ attr.endLine ~ ')' %}
{% endif %}

<details>
<summary><small>Source: {{ lineInfo }}</small></summary>

```php
{{ attr.source|raw }}
```

</details>
{% endif %}
{% endmacro %}

{# Get Modifiers #}

{% macro modifiers(modifiers) %}

{{ modifiers|join('<br>')|raw }}

{% endmacro %}

{# Table Summary #}

{% macro summary(class_) %}

<table style="text-align:left;">
<tr>
<th>Name</th>
<th>Element</th>
<th>Modifier</th>
<th>Description</th>
</tr>
{% if class_.constants %}
{% for const in class_.constants %}
<tr>
<th><a href="#{{ const.name }}"><strong>{{ const.name }}</strong></a></th>
<td>const</td>
<td>{{ _self.modifiers(const.modifiers) }}</td>
<td>{{ const.docs.description.short }}</td>
</tr>
{% endfor %}
{% endif %}

{% if class_.properties %}
{% for prop in class_.properties %}
<tr>
<th><a href="#{{ prop.name }}"><strong>{{ prop.name }}</strong></a></th>
<td>prop</td>
<td>{{ _self.modifiers(prop.modifiers) }}</td>
<td>{{ prop.docs.description.short }}</td>
</tr>
{% endfor %}
{% endif %}

{% if class_.methods %}
{% for method in class_.methods %}
<tr>
<th><a href="#{{ method.name }}"><strong>{{ method.name }}</strong>()</a></th>
<td>method</td>
<td>{{ _self.modifiers(method.modifiers) }}</td>
<td>{{ method.docs.description.short }}</td>
</tr>
{% endfor %}
{% endif %}

</table>

{% endmacro %}

{# Uses #}

{% macro uses(uses) %}
## Uses

<table style="text-align:left;">
{% for alias, class_ in uses %}
<tr>
<td>
{% if apidoc.path(class_) %}
<a href="{{ apidoc.path(class_) }}"><strong>{{ class_ }}</strong></a>
{% else %}
<strong>{{ class_ }}</strong>
{% endif %}
</td>
{# <td>{{ apidoc.path(class_) }}</td> #}
<td>{{ alias }}</td>
</tr>
{% endfor %}
</table>

{% endmacro %}

{# CLASS CONSTANTS #}

{% macro class_constants(constants) %}

{% for const in constants %}
#### ::{{ const.name }}

```php
{{ const.attributes.source|raw }}
```

{% endfor %}

{% endmacro %}

{# PROPERTIES #}

{% macro properties(props) %}

{% for prop in props %}
<hr>

#### ${{ prop.name }}

```php
{{ prop.attributes.source|raw }}
```

{{ _self.docs(prop.docs) }}

{# VARS #}

{% if prop.docs.vars|length > 0 %}
<table>
{% for var_ in prop.docs.vars %}
<tr>
<th style="vertical-align:top;">var</th>
<td>{{ _self.rejoin(var_.type)|raw }}</td>
{% if var.description.body %}
<td>{{ var.description.body|raw }}</td>
{% endif %}
</tr>
{% endfor %}
</table>
{% endif %}


{% endfor %}

{% endmacro %}

{# FUNCTIONS #}

{% macro functions(functions, isMethod = false) %}

{% for funct in functions %}
<hr>

{% if isMethod == true %}
#### {{ funct.name }}()
{% else %}
## {{ funct.name }}()
{% endif  %}

```php
{{ funct.attributes.sourceInline|raw }}
```

{{ _self.docs(funct.docs) }}
{{ _self.params(funct.docs.params) }}

{# RETURNS #}

{% if funct.docs.returns|length > 0 %}
<table>
{% for return_ in funct.docs.returns %}
<tr>
<th style="vertical-align:top;">return</th>
<td>{{ _self.rejoin(return_.type)|raw }}</td>
{% if param.description.body %}
<td>{{ param.description.body|raw }}</td>
{% endif %}
</tr>
{% endfor %}
</table>
{% endif %}

{# THROWS #}

{% if funct.docs.throws|length > 0 %}
<table>
{% for throw_ in funct.docs.throws %}
<tr>
<th style="vertical-align:top;">throw</th>
<td>{{ _self.rejoin(throw_.type)|raw }}</td>
{% if param.description.body %}
<td>{{ param.description.body|raw }}</td>
{% endif %}
</tr>
{% endfor %}
</table>
{% endif %}

{{ _self.source(funct.attributes) }}

{% endfor %}

{% endmacro %}

{# NAMESPACE MEMBERS #}

{% macro siblings(namespace) %}
{% set siblings_ = apidoc.siblings(namespace) %}
{% if siblings_ %}

<details>
<summary style="margin-bottom:12px;"><strong>Members</strong></summary>
<table>
{% for class_ in siblings_ %}
<tr><td><a href="{{ apidoc.path(class_) }}">{{ class_ }}</a></td></tr>
{% endfor %}
</table>
</details>

{% endif %}
{% endmacro %}

{# CLASS INFO #}

{% macro class_info(class_) %}
## {{ class_.fullname }}

<table style="text-align:left">
<tr><th>{{ (class_.modifiers[0] ?? 'class')|ucfirst }}</th><td>{{ class_.name }}</td></tr>
<tr><th>Fully Qualified Name</th><td>{{ class_.fullname }}</td></tr>
{% if class_.extends %}
<tr><th>Extends</th><td><a href="{{ apidoc.path(class_.extends) }}">{{ class_.extends }}</a></td></tr>
{% endif %}
{% if class_.implements %}
<tr><th>Implements</th>
<td>
{% for implement in class_.implements %}
<a href="{{ apidoc.path(implement) }}">{{ implement }}</a><br>
{% endfor %}
</td>
</tr>
{% endif %}
</table>
{% endmacro %}

{# NAVIGATION #}

{% macro navigator(path, bottom=false) %}

<table>
<tr>
<td style="width:100%"><em>{{ path }}</em></td>
{% if bottom == false and apidoc.mode() == 'development' %}
<td><a href="{{ apidoc.jsonPath() }}">json</a></td>
{% endif %}
<td><a href="{{ apidoc.path('/') }}">index</a></td>
<td><a href="{{ apidoc.prevPath() }}">prev</a></td>
<td><a href="{{ apidoc.nextPath() }}">next</a></td>
{% if bottom == true %}<td><a href="#">top</a></td>{% endif %}
</tr>
</table>

{#
{% if bottom == false %}
<table>
<tr><td>Current</td><td>{{ apidoc.currentPath() }}</td></tr>
<tr><td>Index</td><td>{{ apidoc.path('/') }}</td></tr>
<tr><td>Prev</td><td>{{ apidoc.prevPath() }}</td></tr>
<tr><td>Next</td><td>{{ apidoc.nextPath() }}</td></tr>
</tr>
</table>

<pre>
{{ dump(apidoc.refsData())|raw }}
</pre>
{% endif %}
#}

{% endmacro %}

