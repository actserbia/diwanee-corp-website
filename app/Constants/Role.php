<?php
  namespace App\Constants;

  final class Role {
      use ConstantsTrait;

      const User = 'user';
      const Brand = 'brand';
      const Moderator = 'moderator';
      const Admin = 'admin';
  }