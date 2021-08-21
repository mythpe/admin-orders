
<template>
  <div>
    <p class='headline'>{{ $t('progress') }}</p>
  </div>
</template>

<script>

export default {
  name: 'Logout',
  mounted () {
    this.logoutUser(1)
  }
}
</script>
