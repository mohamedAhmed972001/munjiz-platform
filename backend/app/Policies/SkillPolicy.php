<?php

namespace App\Policies;
use App\Models\Profile;
use App\Models\User;

class SkillPolicy
{
  /**
   * Create a new policy instance.
   */
  public function __construct()
  {
    //
  }
  // app/Policies/SkillPolicy.php

  public function manage(User $user, Profile $profile): bool
  {
    // اليوزر يقدر يضيف أو يمسح مهارات فقط لو كان هو صاحب البروفايل
    return $user->id === $profile->user_id;
  }
}
