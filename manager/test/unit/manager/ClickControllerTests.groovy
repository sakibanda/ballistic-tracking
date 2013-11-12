package manager



import org.junit.*
import grails.test.mixin.*

/**
 * ClickControllerTests
 * A unit test class is used to test individual methods or blocks of code without considering the surrounding infrastructure
 */
@TestFor(ClickController)
@Mock(Click)
class ClickControllerTests {


    def populateValidParams(params) {
        assert params != null
        // TODO: Populate valid properties like...
        //params["name"] = 'someValidName'
    }

    void testIndex() {
        controller.index()
        assert "/click/list" == response.redirectedUrl
    }

    void testList() {

        def model = controller.list()

        assert model.clickInstanceList.size() == 0
        assert model.clickInstanceTotal == 0
    }

    void testCreate() {
        def model = controller.create()

        assert model.clickInstance != null
    }

    void testSave() {
        controller.save()

        assert model.clickInstance != null
        assert view == '/click/create'

        response.reset()

        populateValidParams(params)
        controller.save()

        assert response.redirectedUrl == '/click/show/1'
        assert controller.flash.message != null
        assert Click.count() == 1
    }

    void testShow() {
        controller.show()

        assert flash.message != null
        assert response.redirectedUrl == '/click/list'


        populateValidParams(params)
        def click = new Click(params)

        assert click.save() != null

        params.id = click.id

        def model = controller.show()

        assert model.clickInstance == click
    }

    void testEdit() {
        controller.edit()

        assert flash.message != null
        assert response.redirectedUrl == '/click/list'


        populateValidParams(params)
        def click = new Click(params)

        assert click.save() != null

        params.id = click.id

        def model = controller.edit()

        assert model.clickInstance == click
    }

    void testUpdate() {
        controller.update()

        assert flash.message != null
        assert response.redirectedUrl == '/click/list'

        response.reset()


        populateValidParams(params)
        def click = new Click(params)

        assert click.save() != null

        // test invalid parameters in update
        params.id = click.id
        //TODO: add invalid values to params object

        controller.update()

        assert view == "/click/edit"
        assert model.clickInstance != null

        click.clearErrors()

        populateValidParams(params)
        controller.update()

        assert response.redirectedUrl == "/click/show/$click.id"
        assert flash.message != null

        //test outdated version number
        response.reset()
        click.clearErrors()

        populateValidParams(params)
        params.id = click.id
        params.version = -1
        controller.update()

        assert view == "/click/edit"
        assert model.clickInstance != null
        assert model.clickInstance.errors.getFieldError('version')
        assert flash.message != null
    }

    void testDelete() {
        controller.delete()
        assert flash.message != null
        assert response.redirectedUrl == '/click/list'

        response.reset()

        populateValidParams(params)
        def click = new Click(params)

        assert click.save() != null
        assert Click.count() == 1

        params.id = click.id

        controller.delete()

        assert Click.count() == 0
        assert Click.get(click.id) == null
        assert response.redirectedUrl == '/click/list'
    }
}
