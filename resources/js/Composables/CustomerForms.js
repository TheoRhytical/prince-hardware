import { ref } from "vue";

export const signatureImgUrl = ref('')
export const fileSignatureSelect = (event, signature) => {
  const file = event.target.files[0];
  signatureImgUrl.value = URL.createObjectURL(file);
  signature = file
}

export const getModifiedInput = (oldData, newData) => {
  const keys1 = Object.keys(oldData)
  const keys2 = Object.keys(newData)
  if (JSON.stringify(keys1) !== JSON.stringify(keys2)) throw new Error('Keys are not similar');
  let modifiedData = {}
  keys1.forEach(key => {
    if (oldData[key] !== newData[key]) modifiedData[key] = newData[key]
  })
	if (Object.keys(modifiedData).length === 0) throw new Error('No changes to current data');
  modifiedData.id = oldData.id
  return modifiedData
}