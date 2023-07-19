import { Modal } from "bootstrap"
import { get, post } from "./ajax"

window.addEventListener('DOMContentLoaded', function () {
    const editCategoryModal = new Modal(document.getElementById('editCategoryModal'))

    document.querySelectorAll('.edit-category-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            const categoryId = event.currentTarget.getAttribute('data-id')

            get(`/categories/${categoryId}`)
              .then(response => response.json())
                .then(response => openEditCategoryModal(editCategoryModal, response))
        })
    })

    document.querySelector('.save-category-btn').addEventListener('click', function (event) {
        const categoryId = event.currentTarget.getAttribute('data-id')

        post(`/categories/${categoryId}`, {
            name: editCategoryModal._element.querySelector('input[name="name"]').value,
        }, editCategoryModal._element).then(response => {
            if (response.ok) {
                editCategoryModal.hide()
            }
        })
    })
})

function getCsrfFields()
{
    const csrfNameField = document.querySelector('#csrfName')
    const csrfValueField = document.querySelector('#csrfValue')

    const csrfNameKey = csrfNameField.getAttribute('name')
    const csrfName = csrfNameField.content

    const csrfValueKey = csrfValueField.getAttribute('name')
    const csrfValue = csrfValueField.content

    return {
        [csrfNameKey]: csrfName,
        [csrfValueKey]: csrfValue
    }
}

function openEditCategoryModal(modal, {id, name}) {
    const nameInput = modal._element.querySelector('input[name="name"]')

    nameInput.value = name

    modal._element.querySelector('.save-category-btn').setAttribute('data-id', id)

    modal.show()
}
