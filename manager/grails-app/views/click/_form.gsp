<%@ page import="manager.Click" %>



			<div class="control-group fieldcontain ${hasErrors(bean: clickInstance, field: 'apiKey', 'error')} ">
				<label for="apiKey" class="control-label"><g:message code="click.apiKey.label" default="Api Key" /></label>
				<div class="controls">
					<g:textField name="apiKey" value="${clickInstance?.apiKey}"/>
					<span class="help-inline">${hasErrors(bean: clickInstance, field: 'apiKey', 'error')}</span>
				</div>
			</div>

			<div class="control-group fieldcontain ${hasErrors(bean: clickInstance, field: 'url', 'error')} ">
				<label for="url" class="control-label"><g:message code="click.url.label" default="Url" /></label>
				<div class="controls">
					<g:textField name="url" value="${clickInstance?.url}"/>
					<span class="help-inline">${hasErrors(bean: clickInstance, field: 'url', 'error')}</span>
				</div>
			</div>

