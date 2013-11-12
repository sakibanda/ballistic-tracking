package manager

import org.springframework.dao.DataIntegrityViolationException

/**
 * ClickController
 * A controller class handles incoming web requests and performs actions such as redirects, rendering views and so on.
 */
class ClickController {

    static allowedMethods = [save: "POST", update: "POST", delete: "POST"]

    def index() {
        redirect(action: "list", params: params)
    }

    def list() {
        params.max = Math.min(params.max ? params.int('max') : 10, 100)
        [clickInstanceList: Click.list(params), clickInstanceTotal: Click.count()]
    }

    def create() {
        [clickInstance: new Click(params)]
    }

    def save() {
        def clickInstance = new Click(params)
        if (!clickInstance.save(flush: true)) {
            render(view: "create", model: [clickInstance: clickInstance])
            return
        }

        flash.message = message(code: 'default.created.message', args: [message(code: 'click.label', default: 'Click'), clickInstance.id])
        redirect(action: "show", id: clickInstance.id)
    }

    def show() {
        def clickInstance = Click.get(params.id)
        if (!clickInstance) {
            flash.message = message(code: 'default.not.found.message', args: [message(code: 'click.label', default: 'Click'), params.id])
            redirect(action: "list")
            return
        }

        [clickInstance: clickInstance]
    }

    def edit() {
        def clickInstance = Click.get(params.id)
        if (!clickInstance) {
            flash.message = message(code: 'default.not.found.message', args: [message(code: 'click.label', default: 'Click'), params.id])
            redirect(action: "list")
            return
        }

        [clickInstance: clickInstance]
    }

    def update() {
        def clickInstance = Click.get(params.id)
        if (!clickInstance) {
            flash.message = message(code: 'default.not.found.message', args: [message(code: 'click.label', default: 'Click'), params.id])
            redirect(action: "list")
            return
        }

        if (params.version) {
            def version = params.version.toLong()
            if (clickInstance.version > version) {
                clickInstance.errors.rejectValue("version", "default.optimistic.locking.failure",
                        [message(code: 'click.label', default: 'Click')] as Object[],
                        "Another user has updated this Click while you were editing")
                render(view: "edit", model: [clickInstance: clickInstance])
                return
            }
        }

        clickInstance.properties = params

        if (!clickInstance.save(flush: true)) {
            render(view: "edit", model: [clickInstance: clickInstance])
            return
        }

        flash.message = message(code: 'default.updated.message', args: [message(code: 'click.label', default: 'Click'), clickInstance.id])
        redirect(action: "show", id: clickInstance.id)
    }

    def delete() {
        def clickInstance = Click.get(params.id)
        if (!clickInstance) {
            flash.message = message(code: 'default.not.found.message', args: [message(code: 'click.label', default: 'Click'), params.id])
            redirect(action: "list")
            return
        }

        try {
            clickInstance.delete(flush: true)
            flash.message = message(code: 'default.deleted.message', args: [message(code: 'click.label', default: 'Click'), params.id])
            redirect(action: "list")
        }
        catch (DataIntegrityViolationException e) {
            flash.message = message(code: 'default.not.deleted.message', args: [message(code: 'click.label', default: 'Click'), params.id])
            redirect(action: "show", id: params.id)
        }
    }
}
