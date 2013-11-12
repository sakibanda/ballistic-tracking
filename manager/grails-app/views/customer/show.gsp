
<%@ page import="manager.Customer" %>
<!doctype html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="layout" content="kickstart" />
	<g:set var="entityName" value="${message(code: 'customer.label', default: 'Customer')}" />
	<title><g:message code="default.show.label" args="[entityName]" /></title>
</head>

<body>

<section id="show-customer" class="first">

	<table class="table">
		<tbody>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="customer.address.label" default="Address" /></td>
				
				<td valign="top" class="value">${fieldValue(bean: customerInstance, field: "address")}</td>
				
			</tr>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="customer.apiKey.label" default="Api Key" /></td>
				
				<td valign="top" class="value">${fieldValue(bean: customerInstance, field: "apiKey")}</td>
				
			</tr>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="customer.clicKs.label" default="Clic Ks" /></td>
				
				<td valign="top" style="text-align: left;" class="value">
					<ul>
					<g:each in="${customerInstance.clicKs}" var="c">
						<li><g:link controller="click" action="show" id="${c.id}">${c?.encodeAsHTML()}</g:link></li>
					</g:each>
					</ul>
				</td>
				
			</tr>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="customer.createOn.label" default="Create On" /></td>
				
				<td valign="top" class="value"><g:formatDate date="${customerInstance?.createOn}" /></td>
				
			</tr>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="customer.email.label" default="Email" /></td>
				
				<td valign="top" class="value">${fieldValue(bean: customerInstance, field: "email")}</td>
				
			</tr>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="customer.keyEnabled.label" default="Key Enabled" /></td>
				
				<td valign="top" class="value">${fieldValue(bean: customerInstance, field: "keyEnabled")}</td>
				
			</tr>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="customer.name.label" default="Name" /></td>
				
				<td valign="top" class="value">${fieldValue(bean: customerInstance, field: "name")}</td>
				
			</tr>
		
			<tr class="prop">
				<td valign="top" class="name"><g:message code="customer.phone.label" default="Phone" /></td>
				
				<td valign="top" class="value">${fieldValue(bean: customerInstance, field: "phone")}</td>
				
			</tr>
		
		</tbody>
	</table>
</section>

</body>

</html>
