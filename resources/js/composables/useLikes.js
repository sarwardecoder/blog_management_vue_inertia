import { ref } from 'vue'
import axios from 'axios'

export function useLikes(postId) {
  const likeCount = ref(0)
  const isLiked = ref(false)
  const loading = ref(false)
  const error = ref(null)

  const fetchLikes = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await axios.get(`/posts/${postId}/likes`)
      likeCount.value = response.data.like_count
      isLiked.value = response.data.is_liked_by_user
    } catch (err) {
      error.value = err.response?.data?.message || 'Error fetching likes'
    } finally {
      loading.value = false
    }
  }

  const toggleLike = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await axios.post(`/api/posts/${postId}/toggle-like`)
      likeCount.value = response.data.like_count
      isLiked.value = response.data.is_liked_by_user
    } catch (err) {
      error.value = err.response?.data?.message || 'Error toggling like'
    } finally {
      loading.value = false
    }
  }

  return {
    likeCount,
    isLiked,
    loading,
    error,
    fetchLikes,
    toggleLike,
  }
}
