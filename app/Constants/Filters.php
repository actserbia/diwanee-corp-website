<?php
  namespace App\Constants;

  final class Filters {
      use Constants;

      const SearchEqual = 'equal';
      const SearchLike = 'like';
      const SearchEmptyOrNull = 'empty_or_null';
      const SearchGreater = 'greater';
      const SearchGreaterOrEqual = 'greater_or_equal';

      const SearchNotEqual = 'not_equal';
      const SearchNotLike = 'not_like';
      const SearchNotEmptyOrNull = 'not_empty_or_null';
      const SearchLess = 'less';
      const SearchLessOrEqual = 'less_or_equal';


      const ConnectionAnd = 'and';
      const ConnectionOr = 'or';


      const searchTypesText = array(self::SearchEqual, self::SearchLike, self::SearchEmptyOrNull, self::SearchNotEqual, self::SearchNotLike, self::SearchNotEmptyOrNull);
      const searchTypesNumber = array(self::SearchEqual, self::SearchGreater, self::SearchGreaterOrEqual, self::SearchNotEqual, self::SearchLess, self::SearchLessOrEqual);
      const searchTypes = array(
          'text' => self::searchTypesText,
          'number' => self::searchTypesNumber
      );
      const DefaultSearchType = array(
          'text' => self::SearchLike,
          'number' => self::SearchEqual
      );

      const connectionTypes = array(self::ConnectionAnd, self::ConnectionOr);
      const DefaultConnectionType = self::ConnectionOr;

      public static function getSearchTypesForDropdown($type) {
        return self::getForDropdown(self::searchTypes[$type]);
      }

      public static function getConnectionTypesForDropdown() {
        return self::getForDropdown(self::connectionTypes);
      }
  }