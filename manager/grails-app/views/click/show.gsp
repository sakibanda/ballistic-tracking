
<%@ page import="manager.Click" %>
<!doctype html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="layout" content="kickstart" />
	<g:set var="entityName" value="${message(code: 'click.label', default: 'Click')}" />
	<title><g:message code="default.show.label" args="[entityName]" /></title>
</head>

<body>

<section id="show-click" class="first">

	<table class="table">
		<tbody>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="click.apiKey.label" default="Api Key" /></td>
				
				<td valign="top" class="value">${fieldValue(bean: clickInstance, field: "apiKey")}</td>
				
			</tr>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="click.url.label" default="Url" /></td>
				
				<td valign="top" class="value">${fieldValue(bean: clickInstance, field: "url")}</td>
				
			</tr>
		
		</tbody>
	</table>
</section>

</body>

</html>
