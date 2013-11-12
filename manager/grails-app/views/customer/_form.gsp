<%@ page import="manager.Customer" %>



			<div class="control-group fieldcontain ${hasErrors(bean: customerInstance, field: 'address', 'error')} ">
				<label for="address" class="control-label"><g:message code="customer.address.label" default="Address" /></label>
				<div class="controls">
					<g:textField name="address" value="${customerInstance?.address}"/>
					<span class="help-inline">${hasErrors(bean: customerInstance, field: 'address', 'error')}</span>
				</div>
			</div>

			<div class="control-group fieldcontain ${hasErrors(bean: customerInstance, field: 'apiKey', 'error')} ">
				<label for="apiKey" class="control-label"><g:message code="customer.apiKey.label" default="Api Key" /></label>
				<div class="controls">
					<g:textField name="apiKey" value="${customerInstance?.apiKey}"/>
					<span class="help-inline">${hasErrors(bean: customerInstance, field: 'apiKey', 'error')}</span>
				</div>
			</div>

			<div class="control-group fieldcontain ${hasErrors(bean: customerInstance, field: 'clicKs', 'error')} ">
				<label for="clicKs" class="control-label"><g:message code="customer.clicKs.label" default="Clic Ks" /></label>
				<div class="controls">
					<g:select name="clicKs" from="${manager.Click.list()}" multiple="multiple" optionKey="id" size="5" value="${customerInstance?.clicKs*.id}" class="many-to-many"/>
					<span class="help-inline">${hasErrors(bean: customerInstance, field: 'clicKs', 'error')}</span>
				</div>
			</div>

			<div class="control-group fieldcontain ${hasErrors(bean: customerInstance, field: 'createOn', 'error')} required">
				<label for="createOn" class="control-label"><g:message code="customer.createOn.label" default="Create On" /><span class="required-indicator">*</span></label>
				<div class="controls">
					<bs:datePicker name="createOn" precision="day"  value="${customerInstance?.createOn}"  />
					<span class="help-inline">${hasErrors(bean: customerInstance, field: 'createOn', 'error')}</span>
				</div>
			</div>

			<div class="control-group fieldcontain ${hasErrors(bean: customerInstance, field: 'email', 'error')} ">
				<label for="email" class="control-label"><g:message code="customer.email.label" default="Email" /></label>
				<div class="controls">
					<g:textField name="email" value="${customerInstance?.email}"/>
					<span class="help-inline">${hasErrors(bean: customerInstance, field: 'email', 'error')}</span>
				</div>
			</div>

			<div class="control-group fieldcontain ${hasErrors(bean: customerInstance, field: 'keyEnabled', 'error')} ">
				<label for="keyEnabled" class="control-label"><g:message code="customer.keyEnabled.label" default="Key Enabled" /></label>
				<div class="controls">
					<g:textField name="keyEnabled" value="${customerInstance?.keyEnabled}"/>
					<span class="help-inline">${hasErrors(bean: customerInstance, field: 'keyEnabled', 'error')}</span>
				</div>
			</div>

			<div class="control-group fieldcontain ${hasErrors(bean: customerInstance, field: 'name', 'error')} ">
				<label for="name" class="control-label"><g:message code="customer.name.label" default="Name" /></label>
				<div class="controls">
					<g:textField name="name" value="${customerInstance?.name}"/>
					<span class="help-inline">${hasErrors(bean: customerInstance, field: 'name', 'error')}</span>
				</div>
			</div>

			<div class="control-group fieldcontain ${hasErrors(bean: customerInstance, field: 'phone', 'error')} ">
				<label for="phone" class="control-label"><g:message code="customer.phone.label" default="Phone" /></label>
				<div class="controls">
					<g:textField name="phone" value="${customerInstance?.phone}"/>
					<span class="help-inline">${hasErrors(bean: customerInstance, field: 'phone', 'error')}</span>
				</div>
			</div>

